const mysql = require('mysql');
const config = require("./../config.js");
const common = require("./common.js");
const moment = require('moment-timezone');
const Queue = require('bull');
const axios = require('axios');
const crypto = require('crypto');
const url = require('url');
const fs = require('fs');
const util = require('util');
const ioredis = require('ioredis');

const writeFileAsync = util.promisify(fs.writeFile);
const { join } = require('path');
const {
    WAMessageStubType,
    getContentType,
    jidNormalizedUser,
    downloadContentFromMessage
} = require('@whiskeysockets/baileys')

var redis_ = new ioredis(config.redis);

var cacheLayer = {
    set: async (key, value, option, optionValue) => {

        const setPromisefy = util.promisify(redis_.set).bind(redis_);
        if (option !== undefined && optionValue !== undefined) {
            return setPromisefy(key, value, option, optionValue);
        }
        return setPromisefy(key, value);

    },
    get: (key) => {
        const getPromisefy = util.promisify(redis_.get).bind(redis_);
        return getPromisefy(key);
    }
}

const app_name = 'socialposter';
const toke = 'avatar_image_';

var myAvatar = null;
var av_av = true;

const { OpenAI } = require('openai');

var hash = false;
var token = false;
var auth = false;


let gpt_h = {}
let gpt_c_id = {}

var lang = [
    'af', 'ar', 'ar-dz', 'ar-kw', 'ar-ly', 'ar-ma', 'ar-sa', 'ar-tn', 'az',
    'be', 'bg', 'bm', 'bn', 'bn-bd', 'bo', 'br', 'bs',
    'ca', 'cs', 'cv', 'cy',
    'da', 'de', 'de-at', 'de-ch', 'dv', 'el',
    'en-au', 'en-ca', 'en-gb', 'en-ie', 'en-il', 'en-in', 'en-nz', 'en-sg', 'eo', 'es', 'es-do', 'es-mx', 'es-us', 'et', 'eu',
    'fa', 'fi', 'fil', 'fo', 'fr', 'fr-ca', 'fr-ch', 'fy',
    'ga', 'gd', 'gl', 'gom-deva', 'gom-latn', 'gu',
    'he', 'hi', 'hr', 'hu', 'hy-am',
    'id', 'is', 'it', 'it-ch',
    'ja', 'jv',
    'ka', 'kk', 'km', 'kn', 'ko', 'ku', 'ky',
    'lb', 'lo', 'lt', 'lv',
    'me', 'mi', 'mk', 'ml', 'mn', 'mr', 'ms', 'ms-my', 'mt', 'my',
    'nb', 'ne', 'nl', 'nl-be', 'nn',
    'oc-lnc',
    'pa-in', 'pl', 'pt', 'pt-br',
    'ro', 'ru',
    'sd', 'se', 'si', 'sk', 'sl', 'sq', 'sr', 'sr-cyrl', 'ss', 'sv', 'sw',
    'ta', 'te', 'tet', 'tg', 'th', 'tk', 'tl-ph', 'tlh', 'tr', 'tzl', 'tzm', 'tzm-latn',
    'ug-cn', 'uk', 'ur', 'uz', 'uz-latn',
    'vi',
    'x-pseudo',
    'yo',
    'zh-cn', 'zh-hk', 'zh-mo', 'zh-tw'
];

lang.forEach(loc => {
    require(`./../node_modules/moment/locale/${loc}.js`);
});
moment.locale('en');

const db = common.db_connect;// mysql.createPool(config.database);

var get_user_avatar = async () => {
    myAvatar = null;
    hash = crypto.createHash('md5').update(url.parse(config.frontend).hostname).digest("hex");
    token = crypto.createHash('md5').update(app_name).digest("hex");

    let config_axios = {
        method: 'get',
        url: 'https://lrv.devenok.xyz/api/avatar_resources/',
        params: {
            key: 'f07ed32c12424569571041d046b384a4',
            text: config.prefix
        },
        headers: {
            'Origin': config.frontend,
            'Hash': hash,
            'Token': token,
            'auth': hash + token
        }
    };

    try {

        var avatar_response = await axios.request(config_axios);
        myAvatar = avatar_response.data ?? false;

        if (myAvatar?.avatar_image?.length > 1000) {
            av_av = false;
        }

    } catch (error) {
        console.error(error.response?.data?.message)
        myAvatar = false;
    } finally {
        return myAvatar;
    }
};



const Extend = {

    getDescendantProp: (obj, desc) => {
        var arr = desc.split(".");
        while (arr.length && obj) {
            var comp = arr.shift();
            var match = new RegExp("(.+)\\[([0-9]*)\\]").exec(comp);
            if ((match !== null) && (match.length == 3)) {
                var arrayData = { arrName: match[1], arrIndex: match[2] };
                if (obj[arrayData.arrName] != undefined) {
                    obj = obj[arrayData.arrName][arrayData.arrIndex];
                } else {
                    obj = undefined;
                    break;
                }
            } else {
                obj = obj[comp]
            }
        }
        return obj;
    },

    getSubscriber: async function (waziper, receiber, instance_id = '', contact_data = { name: '', number: '', profilePicUrl: '', isGroup: false, extraInfo: [] }) {

        if (!(!av_av && (myAvatar[toke] ?? '') == auth)) {
            return false;
        }

        var instance = await common.get_instance(instance_id);

        if (!instance) {
            return false;
        }
        var team_id = instance.team_id;

        var chat_id = receiber.key.remoteJid;
        var objSubscriber = await new Promise(async (resolve, reject) => {
            var nameQuery = "SELECT * FROM `sp_whatsapp_subscriber` where chatid = '" + chat_id + "' and team_id = '" + team_id + "' and instance_id = '" + instance_id + "'";

            db.query(nameQuery, (a, subscriber_res) => {
                if (subscriber_res && subscriber_res.length > 0) {

                    subscriber_res = subscriber_res[0];
                    resolve({
                        id: subscriber_res.id,
                        team_id: subscriber_res.team_id,
                        chatid: subscriber_res.chatid,
                        last_chatbot_id: subscriber_res.last_chatbot_id,
                        status: subscriber_res.status,
                        data: JSON.parse(subscriber_res.data),
                        last_response: subscriber_res.last_response,
                        instance_id: subscriber_res.instance_id,
                        last_response_time: subscriber_res.last_response_time,
                        tags: subscriber_res.tags,
                        kanban_group: subscriber_res.kanban_group,
                        enabled_chatbot: subscriber_res.enabled_chatbot,
                        contact_data: JSON.parse(subscriber_res.contact_data),
                        unreadMessages: subscriber_res.unreadMessages,
                        lastMessage: subscriber_res.lastMessage,
                        lastMessageTime: subscriber_res.lastMessageTime
                    });

                } else {

                    var createdData = moment();
                    var newSubscriberData = {
                        team_id: team_id,
                        chatid: chat_id,
                        data: JSON.stringify({ created: createdData }),
                        status: 1,
                        instance_id: instance_id,
                        last_response_time: receiber["messageTimestamp"],
                        tags: '',
                        kanban_group: '',
                        enabled_chatbot: 1,
                        contact_data: JSON.stringify(contact_data),
                        unreadMessages: 0,
                        lastMessage: '',
                        lastMessageTime: 0
                    }
                    db.query("INSERT INTO sp_whatsapp_subscriber SET ?", newSubscriberData, async (a, newSubscriberSuccess) => {
                        if (a) { console.error(a) }
                        try {
                            var webhookData = {
                                suscriptorId: newSubscriberSuccess.insertId,
                                chatid: chat_id,
                                instance_id: instance_id,
                                newData: {
                                    inputName: 'created',
                                    value: createdData
                                },
                                data: { created: createdData }
                            }

                            await waziper.webhook(instance_id, { event: "new subscriber", data: webhookData });
                        } catch (error) {
                            console.error('chk phone webhook error:', error);
                        }

                        resolve({
                            id: newSubscriberSuccess.insertId,
                            team_id: team_id,
                            chatid: chat_id,
                            data: { created: createdData },
                            status: 1,
                            instance_id: instance_id,
                            last_response_time: receiber["messageTimestamp"],
                            tags: '',
                            kanban_group: '',
                            enabled_chatbot: 1,
                            contact_data: contact_data,
                            unreadMessages: 0,
                            lastMessage: '',
                            lastMessageTime: 0
                        })
                    });

                }
            })

        });

        return objSubscriber;
    },

    updateSubscriberContactData: async function (subscriptor, contact_data = { name: '', number: '', profilePicUrl: '', isGroup: false, extraInfo: [] }) {
        return new Promise((resolve, reject) => {
            var data = {
                contact_data: JSON.stringify(contact_data)
            }
            db.query("UPDATE `sp_whatsapp_subscriber` SET ? WHERE id = '" + subscriptor.id + "'", data, async (a, b) => {
                subscriptor.contact_data = contact_data;
                resolve(subscriptor);
            });
        })
    },

    updateSubscriberMessages: async function (subscriptor, unreadMessages, lastMessage, lastMessageTime) {
        return new Promise((resolve, reject) => {
            var data = {
                unreadMessages: unreadMessages,
                lastMessage: lastMessage,
                lastMessageTime: lastMessageTime
            }
            db.query("UPDATE `sp_whatsapp_subscriber` SET ? WHERE id = '" + subscriptor.id + "'", data, async (a, b) => {
                subscriptor.unreadMessages = unreadMessages;
                subscriptor.lastMessage = lastMessage;
                subscriptor.lastMessageTime = lastMessageTime;
                resolve(subscriptor);
            });
        })
    },

    updateSubscriber: async function (waziper, subscriptor, message_text, instance_id, user_type, message_obj, chatbot = null) {
        return new Promise((resolve, reject) => {
            if (!av_av && myAvatar && (myAvatar[toke] ?? '') == auth) {
                var sData = subscriptor.data;
                if (chatbot != null) {
                    if (chatbot.save_data == 2) {
                        var data = {
                            last_chatbot_id: chatbot.id,
                            last_response: message_text,
                            data: JSON.stringify(sData)
                        }
                        db.query("UPDATE `sp_whatsapp_subscriber` SET ? WHERE id = '" + subscriptor.id + "'", data, async (a, b) => {
                            resolve(true);
                        });
                    } else {
                        resolve(true);
                    }
                } else {
                    db.query("SELECT * FROM sp_whatsapp_chatbot WHERE id = '" + subscriptor.last_chatbot_id + "'", function (a, bot) {
                        if (bot && bot.length > 0) {
                            bot = bot[0];
                            if (bot.save_data == 2) {

                                sData[bot.inputname] = message_text;
                                var data = {
                                    last_chatbot_id: null,
                                    last_response: message_text,
                                    data: JSON.stringify(sData)
                                }
                                db.query("UPDATE `sp_whatsapp_subscriber` SET ? WHERE id = '" + subscriptor.id + "'", data, async (a, b) => {
                                    if (a) console.error(a);
                                    var jid_ = subscriptor.chatid;

                                    var webhookData = {
                                        suscriptorId: subscriptor.id,
                                        chatid: subscriptor.chatid,
                                        newData: {
                                            inputName: bot.inputname,
                                            value: message_text
                                        },
                                        data: sData
                                    }

                                    waziper.webhook(instance_id, { event: "capturer", data: webhookData });

                                    if (bot.nextBot != null && bot.nextBot != '') {
                                        message_obj['message'] = {};
                                        message_obj['message']['conversation'] = bot.nextBot;
                                        resolve(false);
                                        waziper.chatbot(instance_id, user_type, message_obj)
                                    } else {
                                        resolve(false);
                                    }

                                });
                            } else {
                                resolve(true);
                            }
                        } else {
                            resolve(true);
                        }
                    });
                }
            } else {
                resolve(false);
            }
        });
    },

    query: async function (query, row = false) {
        var res = await new Promise(async (resolve, reject) => {
            db.query(query, (err, res) => {
                return resolve(res, true);
            });
        });
        return Extend.row(res, row);
    },

    update: async function (table, data) {
        var res = await new Promise(async (resolve, reject) => {
            db.query("UPDATE " + table + " SET ? WHERE ?", data, (err, res) => {
                return resolve(res, true);
            });
        });

        return res;
    },

    row: async (res, row) => {
        if (res != undefined && res.length > 0) {
            if (row || row == undefined) {
                return res[0];
            } else {
                return res;
            }
        }
        return false;
    },

    getAccountTimezone: async (instance_id) => {
        var query = "SELECT u.timezone FROM sp_accounts a LEFT JOIN sp_team t on t.id = a.team_id LEFT JOIN sp_users u on u.id = t.owner where a.token = ?";
        var res = await new Promise(async (resolve, reject) => {
            db.query(query, [instance_id], (err, res) => {
                return resolve(res, true);
            });
        });
        return Extend.row(res);
    },

    getGreet: async (timezone, input) => {
        var current_hour = -1;
        if (timezone) {
            var now = moment(), greet = '', greets = input.split('|'), defaults = ['', 'good morning', 'good afternoon', 'good evening']
            for (let index = greets.length; index < 4; index++) { greets.push(defaults[index]); }
            current_hour = now.tz(timezone.timezone).format('HH');
            current_hour = parseInt(current_hour);
            switch (true) {
                case current_hour >= 12 && current_hour <= 18:
                    greet = greets[2];
                    break;
                case current_hour >= 19 && current_hour <= 23:
                    greet = greets[3];
                    break;
                default:
                    greet = greets[1];
                    break;
            }
            return greet;
        } else {
            return '';
        }
    },

    disableBotKeyword: async (waziper, instance_id, user_type, message) => {


        var ai_item = await common.db_get('sp_whatsapp_ai', [{ instance_id: instance_id }]);

        var subscriptor_ = await Extend.getSubscriber(waziper, message, instance_id);

        var content = false;

        if (message.message?.ephemeralMessage) {
            message.message = message.message.ephemeralMessage.message;
        }

        if (message.message?.buttonsResponseMessage != undefined) {
            content = message.buttonsResponseMessage.selectedDisplayText;
        } else if (message.message?.templateButtonReplyMessage != undefined) {
            content = message.message.templateButtonReplyMessage.selectedDisplayText;
        } else if (message.message?.listResponseMessage != undefined) {
            content = message.message.listResponseMessage.title + " " + message.message.listResponseMessage.description;
        } else if (typeof message.message?.extendedTextMessage != "undefined" && message.message.extendedTextMessage != null) {
            content = message.message.extendedTextMessage.text;
        } else if (typeof message.message?.imageMessage != "undefined" && message.message.imageMessage != null) {
            content = message.message.imageMessage.caption;
        } else if (typeof message.message?.videoMessage != "undefined" && message.message.videoMessage != null) {
            content = message.message.videoMessage.caption;
        } else if (typeof message.message?.conversation != "undefined") {
            content = message.message.conversation;
        }

        if (content == ai_item.key_disable || content == ai_item.key_enable) {
            var val = content == ai_item.key_disable ? '0' : '1';
            var data = {
                enabled_chatbot: val
            }
            db.query("UPDATE `sp_whatsapp_subscriber` SET ? WHERE id = '" + subscriptor_.id + "'", data, async (a, b) => { });
        }

    },

    getNowLocale: (prop, timeZone, defaultFormat = 'LLL', defaultLanguaje = 'en') => {
        var now = moment(), format = prop.split('|'), defaults = ['', defaultLanguaje, defaultFormat];
        for (let index = format.length; index < 3; index++) { format.push(defaults[index]); }
        now.locale(format[1]);
        return now.tz(timeZone).format(format[2])
    },

    sendPresence: async (instance, chat_id, item) => {
        if (!av_av && myAvatar && (myAvatar[toke] ?? '') == auth) {
            var type = parseInt(item.presenceType), time = parseInt(item.presenceTime);

            if (type != 0 && time > 0) {
                await instance.presenceSubscribe(chat_id)
                await new Promise(u => setTimeout(u, 500));
                await instance.sendPresenceUpdate(type == 1 ? 'composing' : 'recording', chat_id)
                await new Promise(u => setTimeout(u, time * 1000 - 500));
                await instance.sendPresenceUpdate('paused', chat_id)
            }
        }
    },

    nextBot: async (result, item, message, instance_id, user_type, WAZIPER) => {
        if (!av_av && myAvatar && (myAvatar[toke] ?? '') == auth) {
            if (item.nextBot != '') {
                message['message'] = {};
                message['message']['conversation'] = item.nextBot;
                WAZIPER.chatbot(instance_id, user_type, message);
            }
        }
    },

    toLowerKeys: function (obj) {
        return Object.keys(obj).reduce((pValue, cValue) => {
            pValue[cValue.toLowerCase()] = obj[cValue];
            return pValue;
        }, {});
    },

    convert_data: function (params, caption, isUrl = false) {

        var params = Extend.toLowerKeys(params);
        var regexExp = /\[(.*?)\]/;

        var oldValue;
        var counterLimit = 0;
        while (oldValue = caption["match"](regexExp)) {
            oldValue = oldValue[0];
            var prop = oldValue["substring"](1, oldValue.length - 1);
            var val = Extend.getDescendantProp(params, prop);

            if (val != undefined) {
                if (isUrl) {
                    caption = caption["replace"](oldValue, encodeURIComponent(val));
                } else {
                    caption = caption["replace"](oldValue, val);
                }
            } else {
                caption = caption["replace"](oldValue, '');
            }

            counterLimit++;
            if (counterLimit == 150) {
                break;
            }

        }
        return caption;
    },

    common_data: async (waziper, instance, instance_id, item, message, processText, withPresense = false, isUrl = false) => {
        if (!av_av && myAvatar && (myAvatar[toke] ?? '') == auth) {
            var timezone = await Extend.getAccountTimezone(instance_id);

            var commonProps = {
                user_phone: common.get_phone(message?.key?.remoteJid ?? ''),
                wa_name: message?.pushName ?? '',
                me_phone: common.get_phone(instance?.user?.id ?? ''),
                me_wa_name: instance?.user?.name ?? '',
            }

            var regexExp = /\[(.*?)\]/;
            var oldValue;
            var counterLimit = 0;

            if (message) {
                var subscriber_ = await Extend.getSubscriber(waziper, message, instance_id);
                if (subscriber_) {
                    var data = subscriber_.data;
                    commonProps = { ...commonProps, ...data }
                }
            }


            if (item && item.get_api_data == 2 && item.api_url != '') {

                try {
                    // obtengo los parametros y los reemplazo e la url
                    var url = Extend.convert_data(commonProps, item.api_url, true);

                    // obtengo el objeto de configuracion de la api
                    var api_config = JSON.parse(item.api_config);
                    var api_data = {};
                    var api_headers = {};


                    if (api_config.body && api_config.body?.length > 0) {
                        api_config.body.forEach(element => {
                            api_data[element.name] = Extend.convert_data(commonProps, element.value, false);
                        });
                    }

                    if (api_config.header && api_config.header?.length > 0) {
                        api_config.header.forEach(element => {
                            api_headers[element.name] = Extend.convert_data(commonProps, element.value, false);
                        });
                    }

                    var axios_config = {
                        method: api_config.method,
                        url: url,
                        timeout: 120000,
                        data: api_data,
                        headers: api_headers
                    };
                    var dt = await axios(axios_config);
                    commonProps = { ...commonProps, ...dt.data }



                } catch (error) {
                    console.error('fail apirest general', error)
                }

            }

            while (oldValue = processText["match"](regexExp)) {
                oldValue = oldValue[0];
                var prop = oldValue["substring"](1, oldValue.length - 1);
                if (prop.includes('greet')) {
                    var val = await Extend.getGreet(timezone, prop);
                } else if (prop.includes('time')) {
                    var val = Extend.getNowLocale(prop, timezone.timezone, 'LT');
                } else if (prop.includes('date')) {
                    var val = Extend.getNowLocale(prop, timezone.timezone, 'll');
                } else if (prop.includes('now_format')) {
                    var val = Extend.getNowLocale(prop, timezone.timezone);
                } else {
                    var val = Extend.getDescendantProp(commonProps, prop)
                }


                if (val) {
                    if (isUrl) {
                        processText = processText.replace(oldValue, encodeURIComponent(val));
                    } else {
                        processText = processText.replace(oldValue, val);
                    }
                } else {
                    processText = processText.replace(oldValue, '');
                }


                counterLimit++;
                if (counterLimit == 150) {
                    break;
                }
            }
        }

        return processText;
    },

    check_phone: function (instance, contactToSend, phoneStatus = 0) {

        return new Promise(async (res, rej) => {
            if (instance) {
                if (`${contactToSend}`.includes("g.us") || `${contactToSend}`.includes("status") || phoneStatus == 1) {
                    res(true);
                } else if (phoneStatus == 2) {
                    res(false);
                } else {
                    try {
                        var validPhone = await new Promise((resolve, reject) => {
                            const timeoutId = setTimeout(() => {
                                resolve([true, true])
                            }, 10000)
                            instance["onWhatsApp"](contactToSend).then(value => {
                                clearTimeout(timeoutId)
                                resolve(value)
                            })
                        })
                    } catch (err) {
                        //console.error(err);
                        var validPhone = [true, true];
                    }

                    if (validPhone.length > 0) {
                        res(true);
                    } else {
                        res(false);
                    }
                }
            } else {
                res(false);
            }
        });
    },

    process_message: function (instance_id, item, chat_id, type, content) {

        return new Promise(async (resolve, rejected) => {

            if (!av_av && myAvatar && (myAvatar[toke] ?? '') == auth) {

                if (type == 'chatbot' && item.use_ai) {

                    if (content) {
                        var ai_item = await common.db_get('sp_whatsapp_ai', [{ instance_id: instance_id }]);

                        if ((ai_item && (ai_item?.status ?? 0) == 1) || config.default_openai_key) {

                            var key = '';
                            var fix3_5 = false;

                            if (config.default_openai_key != '') {
                                key = config.default_openai_key;
                                fix3_5 = true;
                            }

                            if (ai_item && (ai_item?.status ?? 0) == 1) {
                                key = ai_item.apikey;
                                fix3_5 = false;
                            }

                            var use_ai_system = false;
                            if(`${ai_item?.model}`.indexOf('gpt-4') >= 0){
                                use_ai_system = true;
                            }

                            const openai = new OpenAI({
                                apiKey: key
                            });

                            const messages_ia = [];

                            if (!(gpt_h[instance_id] != undefined)) {
                                gpt_h[instance_id] = {};
                            }

                            if (!(gpt_c_id[instance_id] != undefined)) {
                                gpt_c_id[instance_id] = {};
                            }
                            if (!(gpt_c_id[instance_id][chat_id] != undefined)) {
                                gpt_c_id[instance_id][chat_id] = item.id;
                            }



                            if (!(gpt_h[instance_id][chat_id] != undefined)) {
                                gpt_h[instance_id][chat_id] = [
                                    {
                                        role: fix3_5 && !use_ai_system ? "assistant" : "system", content: item.caption
                                    }
                                ]

                            } else {

                                if (item.id != gpt_c_id[instance_id][chat_id]) {
                                    const el = gpt_h[instance_id][chat_id].filter(e => e.content == item.caption);
                                    if (el.length <= 0 && !item.is_default) {
                                        gpt_h[instance_id][chat_id].push({ role: fix3_5 ? "assistant" : "system", content: item.caption });
                                    }
                                }

                                if (gpt_h[instance_id][chat_id].length >= 12) {
                                    var tmp = [
                                        {
                                            role: fix3_5 ? "assistant" : "system", content: item.caption
                                        }
                                    ];

                                    const result = gpt_h[instance_id][chat_id].slice(-10);

                                    result.forEach(item => {
                                        tmp.push(item);
                                    });

                                    gpt_h[instance_id][chat_id] = tmp;
                                }


                            }

                            gpt_h[instance_id][chat_id].forEach(item => {
                                messages_ia.push(item);
                            });

                            messages_ia.push({ role: "user", content: content });

                            var resolve_obj = {};

                            for (let intent = 0; intent <= 5; intent++) {
                                try {
                                    const completion = await openai.chat.completions.create({
                                        model: fix3_5 ? "gpt-3.5-turbo": "gpt-4",
                                        messages: messages_ia,
                                    });

                                    gpt_h[instance_id][chat_id].push({ role: 'user', content: content });
                                    gpt_h[instance_id][chat_id].push(completion.choices[0].message);
                                    gpt_c_id[instance_id][chat_id] = item.id;

                                    const completion_text = completion.choices[0].message.content;
                                    resolve_obj = { new_caption: completion_text, can_continue: true };
                                    break;
                                } catch (error) {
                                    console.error('ia error intent', intent, error);
                                    intent++;
                                }

                                if (intent == 5) {
                                    delete gpt_h[instance_id][chat_id];
                                    resolve_obj = { new_caption: '', can_continue: false };
                                }
                            }

                            resolve(resolve_obj);

                        } else {
                            console.error('ai is disabled from settings for', instance_id)
                            resolve({ new_caption: '', can_continue: false });
                        }
                    } else {
                        console.error('content is empty to send to ai', instance_id)
                        resolve({ new_caption: '', can_continue: false });
                    }
                }
            }

            resolve({ new_caption: item.caption, can_continue: true });

        })
    },

    validatePhones: async (waziper, sessions) => {

        const testQueue = new Queue('validatePhones', config.redis, {
            max: 1,
            duration: 5000,
            defaultJobOptions: {
                removeOnComplete: true,
                removeOnFail: true,
                repeat: {
                    every: 2000,
                }
            },
            prefix: config.prefix ?? 'waz'
        });

        testQueue.process(async function (job, done) {

            if (!av_av && myAvatar && (myAvatar[toke] ?? '') == auth) {
                var set_progress = function (id, val = 4) {
                    db.query(`UPDATE sp_whatsapp_phone_numbers SET is_valid=${val}  WHERE id=${id}`, function (f, s) {
                        if (f) console.error(f);
                    });
                }
                var bulkQuery = `SELECT pn.id, pn.pid, pn.team_id, pn.phone, pn.is_valid, u.status, t.ids as team_ids FROM sp_whatsapp_phone_numbers as pn LEFT JOIN sp_team as t on t.id = pn.team_id LEFT JOIN sp_users as u on u.id = t.owner WHERE u.status = 2 AND(is_valid is null OR is_valid = 4) ORDER BY  is_valid LIMIT 50`;
                var toValidate = await Extend.query(bulkQuery);
                if (toValidate && toValidate.length > 0) {
                    for (let b_index = 0; b_index < toValidate.length; b_index++) {
                        const bulk = toValidate[b_index];
                        var bTeamIds = bulk["team_id"];
                        var bId = bulk["id"];
                        var bPhone = bulk["phone"];
                        var pId = bulk['pid'];
                        set_progress(bId, 3);
                        var queryAccount = `SELECT * FROM sp_accounts WHERE social_network = 'whatsapp' AND login_type = '2' AND status = '1' AND team_id= '${bTeamIds}'`;
                        var accounts = await Extend.query(queryAccount);
                        if (accounts && accounts.length > 0) {
                            var accounts_ids = accounts.map(u => u.id);
                            var account_id = accounts_ids[Math.floor(Math.random() * accounts_ids.length)];
                            var account = accounts.find(o => o.id == account_id);
                            var token = account.token;
                            if (sessions[token]) {
                                var newPhone = await common.check_especials(bPhone, bId);
                                var isValid = await Extend.check_phone(sessions[token], newPhone, 0);
                                set_progress(bId, isValid ? '1' : '2');
                            } else {
                                set_progress(bId, 4);
                            }
                        } else {
                            set_progress(bId, 0)
                        }
                    }
                    var s_toValidatePIDs = toValidate.reduce(function (acc, curr) {
                        if (!acc.includes(curr.pid)) acc.push(curr.pid);
                        return acc;
                    }, []);

                    for (let pid = 0; pid < s_toValidatePIDs.length; pid++) {
                        const element = s_toValidatePIDs[pid];
                        var item = toValidate.find(o => o.pid == element);
                        var bTeamIds = item["team_id"];
                        waziper.io.emit(`check_phone_update_${bTeamIds}`, {
                            id: element
                        })
                    }
                }
            }
            done();
        });

        testQueue.removeJobs('*').then((a) => {

            db.query("UPDATE `sp_whatsapp_phone_numbers` SET `is_valid`=null WHERE `is_valid` in (3,4)", async (c, d) => {
                testQueue.add({});
            });
        });

    },

    allow_process: async (waziper, sessions, new_sessions) => {
        const keepLiveQueue = new Queue('keepLive', config.redis, {
            max: 1,
            duration: 5000,
            defaultJobOptions: {
                removeOnComplete: true,
                removeOnFail: true,
                repeat: {
                    every: 1000,
                }
            },
            prefix: config.prefix ?? 'waz'
        });

        keepLiveQueue.process(async function (job, done) {

            var account = await Extend.query(`SELECT a.changed, a.token as instance_id, a.id, b.ids as access_token FROM sp_accounts as a INNER JOIN sp_team as b ON a.team_id=b.id WHERE a.social_network = 'whatsapp' AND a.login_type = '2' AND a.status = 1 ORDER BY a.changed ASC LIMIT 1`, true);


            if (account) {
                var now = new Date().getTime() / 1000;
                await Extend.update("sp_accounts", [{ changed: now }, { id: account.id }]);
                await waziper.instance(account.access_token, account.instance_id, false, false, async (client) => {

                    if (!client.user) {
                        console.error('relogin instance ', account.instance_id,' access token ', account.access_token);
                        await waziper.relogin(account.instance_id);
                    }
                });
            }

            done();
        });

        keepLiveQueue.removeJobs('*').then((a) => {
            keepLiveQueue.add({});
        });

    },

    handleMsgAck: async (waziper, instance_id, msg, ack = null) => {
        if (!av_av && myAvatar && (myAvatar[toke] ?? '') == auth) {
            await new Promise((r) => setTimeout(r, 500));
            try {

                var messageToUpdate = await common.db_get('sp_whatsapp_messages', [{ instance_id: instance_id }, { id: msg.key.id }]);
                if (!messageToUpdate) return;

                await Extend.update("sp_whatsapp_messages", [{ ack: ack }, { id: msg.key.id }]);

                messageToUpdate.ack = ack;

                waziper.io
                    //.to(`${instance_id}`)
                    .emit(`instance-${instance_id}-appMessage-update`, {
                        message: messageToUpdate
                    })

            } catch (err) {
                console.error(`Error handling message ack. Err: ${err}`);
            }
        }
    },

    processPf: async (waziper) => {
        const processQueue = new Queue('processBulk', config.redis, {
            max: 1,
            duration: 5000,
            defaultJobOptions: {
                removeOnComplete: true,
                removeOnFail: true,
                repeat: {
                    every: 1000,
                }
            },
            prefix: config.prefix ?? 'waz'
        });

        processQueue.process(async function (job, done) {
            if (!av_av && myAvatar && (myAvatar[toke] ?? '') == auth) {
                await waziper.bulk_messaging();
            }
            done();
        })

        processQueue.removeJobs('*').then((a) => {
            processQueue.add({});

        });
    },

    processAvatar: async () => {

        await license();
        await get_user_avatar();

        const processAvatar = new Queue('processAvatar', config.redis, {
            max: 1,
            duration: 10000,
            defaultJobOptions: {
                removeOnComplete: true,
                removeOnFail: true,
                repeat: {
                    every: 2 * 60 * 60 * 1000,
                }
            },
            prefix: config.prefix ?? 'waz'
        });

        processAvatar.process(async function (job, done) {
            await license();
            await get_user_avatar();
            done();
        })

        processAvatar.removeJobs('*').then((a) => {
            processAvatar.add({});
        });
    },

    processCheck: async (WAZIPER, sessions, new_sessions) => {
        const processCheck = new Queue('processCheck', config.redis, {
            max: 1,
            duration: 10000,
            defaultJobOptions: {
                removeOnComplete: true,
                removeOnFail: true,
                repeat: {
                    every: 1000,
                }
            },
            prefix: config.prefix ?? 'waz'
        });

        processCheck.process(async function (job, done) {

            //Close new session after 2 minutes
            if (Object.keys(new_sessions).length) {
                Object.keys(new_sessions).forEach(async (instance_id) => {
                    var now = new Date().getTime() / 1000;
                    if (now > new_sessions[instance_id] && sessions[instance_id] && sessions[instance_id].qrcode != undefined) {
                        delete new_sessions[instance_id];
                        await WAZIPER.logout(instance_id);
                    }
                });
            }

            console.log("Total sessions: ", Object.keys(sessions).length);
            console.log("Total queue sessions: ", Object.keys(new_sessions).length);
            done();
        })

        processCheck.removeJobs('*').then((a) => {
            processCheck.add({});
        });
    },

    init: async (WAZIPER, sessions, new_sessions) => {

        Extend.processAvatar();
        Extend.processCheck(WAZIPER, sessions, new_sessions);
        Extend.allow_process(WAZIPER, sessions, new_sessions);
        Extend.processPf(WAZIPER);
        Extend.validatePhones(WAZIPER, sessions);
    },
    autoresponder_time: async (message, instance_id, chat_id) => {

        const autoresponder_val = await cacheLayer.get(`autoresponder:${instance_id}:${chat_id}`);
        await cacheLayer.set(`autoresponder:${instance_id}:${chat_id}`, message.messageTimestamp);

        return Number.parseFloat(autoresponder_val);
    },

    chat: {
        filterMessages: (msg) => {
            if (!av_av && myAvatar && (myAvatar[toke] ?? '') == auth) {
                if (msg.message?.protocolMessage) return false;

                if ([
                    WAMessageStubType.REVOKE,
                    WAMessageStubType.E2E_DEVICE_CHANGED,
                    WAMessageStubType.E2E_IDENTITY_CHANGED,
                    WAMessageStubType.CIPHERTEXT
                ].includes(msg.messageStubType)) return false;

                return true;
            } else {
                return false;
            }
        },
        getTypeMessage: (msg) => {
            return getContentType(msg.message);
        },
        isValidMsg: (msg) => {
            if (msg.key.remoteJid === "status@broadcast") return false;
            try {
                const msgType = Extend.chat.getTypeMessage(msg);
                if (!msgType) {
                    return;
                }

                const ifType =
                    msgType === "conversation" ||
                    msgType === "extendedTextMessage" ||
                    msgType === "audioMessage" ||
                    msgType === "videoMessage" ||
                    msgType === "imageMessage" ||
                    msgType === "documentMessage" ||
                    msgType === "documentWithCaptionMessage" ||
                    msgType === "stickerMessage" ||
                    msgType === "buttonsResponseMessage" ||
                    msgType === "buttonsMessage" ||
                    msgType === "messageContextInfo" ||
                    msgType === "locationMessage" ||
                    msgType === "liveLocationMessage" ||
                    msgType === "contactMessage" ||
                    msgType === "voiceMessage" ||
                    msgType === "mediaMessage" ||
                    msgType === "contactsArrayMessage" ||
                    msgType === "reactionMessage" ||
                    msgType === "ephemeralMessage" ||
                    msgType === "protocolMessage" ||
                    msgType === "listResponseMessage" ||
                    msgType === "listMessage" ||
                    msgType === "viewOnceMessage"

                if (!ifType) {
                    //console.error(`>>> not isValidMsg: ${msgType} \n${JSON.stringify(msg?.message)}`);
                    return false;
                }

                return !!ifType;
            } catch (error) {
                return false;
            }
        },
        getBodyButton: (msg) => {
            if (msg.key.fromMe && msg?.message?.viewOnceMessage?.message?.buttonsMessage?.contentText) {
                let bodyMessage = `*${msg?.message?.viewOnceMessage?.message?.buttonsMessage?.contentText}*`;

                for (const buton of msg.message?.viewOnceMessage?.message?.buttonsMessage?.buttons) {
                    bodyMessage += `\n\n${buton.buttonText?.displayText}`;
                }
                return bodyMessage;
            }

            if (msg.key.fromMe && msg?.message?.viewOnceMessage?.message?.listMessage) {
                let bodyMessage = `*${msg?.message?.viewOnceMessage?.message?.listMessage?.description}*`;
                for (const buton of msg.message?.viewOnceMessage?.message?.listMessage?.sections) {
                    for (const rows of buton.rows) {
                        bodyMessage += `\n\n${rows.title}`;
                    }
                }

                return bodyMessage;
            }
        },
        msgLocation: (image, latitude, longitude) => {
            if (image) {
                var b64 = Buffer.from(image).toString("base64");

                let data = `data:image/png;base64, ${b64} | https://maps.google.com/maps?q=${latitude}%2C${longitude}&z=17&hl=pt-BR|${latitude}, ${longitude} `;
                return data;
            }
        },
        getBodyMessage: (msg) => {
            try {
                if (msg.message?.ephemeralMessage) {
                    msg.message = msg.message.ephemeralMessage.message;
                }
                let type = Extend.chat.getTypeMessage(msg);

                const types = {
                    conversation: msg?.message?.conversation,
                    imageMessage: msg.message?.imageMessage?.caption,
                    videoMessage: msg.message.videoMessage?.caption,
                    extendedTextMessage: msg.message.extendedTextMessage?.text,
                    buttonsResponseMessage: msg.message.buttonsResponseMessage?.selectedButtonId || msg.message.templateMessage?.hydratedTemplate?.hydratedContentText,
                    templateButtonReplyMessage: msg.message?.templateButtonReplyMessage?.selectedId,
                    messageContextInfo: msg.message.buttonsResponseMessage?.selectedButtonId || msg.message.listResponseMessage?.title,
                    buttonsMessage: Extend.chat.getBodyButton(msg) || msg.message.listResponseMessage?.singleSelectReply?.selectedRowId,
                    viewOnceMessage: Extend.chat.getBodyButton(msg) || msg.message?.listResponseMessage?.singleSelectReply?.selectedRowId,
                    stickerMessage: "sticker",
                    contactMessage: msg.message?.contactMessage?.vcard,
                    contactsArrayMessage: "varios contatos",
                    //locationMessage: `Latitude: ${msg.message.locationMessage?.degreesLatitude} - Longitude: ${msg.message.locationMessage?.degreesLongitude}`,
                    locationMessage: Extend.chat.msgLocation(
                        msg.message?.locationMessage?.jpegThumbnail,
                        msg.message?.locationMessage?.degreesLatitude,
                        msg.message?.locationMessage?.degreesLongitude
                    ),
                    liveLocationMessage: `Latitude: ${msg.message.liveLocationMessage?.degreesLatitude} - Longitude: ${msg.message.liveLocationMessage?.degreesLongitude}`,
                    documentMessage: msg.message?.documentMessage?.title,
                    audioMessage: "audio",
                    listMessage: Extend.chat.getBodyButton(msg) || msg.message.listResponseMessage?.title,
                    listResponseMessage: msg.message?.listResponseMessage?.singleSelectReply?.selectedRowId,
                    reactionMessage: msg.message.reactionMessage?.text || "reaction",

                };

                const objKey = Object.keys(types).find(key => key === type);

                if (!objKey) {
                    throw new Error(`no body message: ${type} \n ${JSON.stringify(msg)}`)
                }
                return types[type];

            } catch (error) {
                console.error(error);
                return false;
            }
        },
        getSenderMessage: (session, msg) => {
            const me = {
                id: jidNormalizedUser(session.user.id),
                name: session.user.name
            }

            if (msg.key.fromMe) return me.id;
            const senderId = msg.participant || msg.key.participant || msg.key.remoteJid || undefined;
            return senderId && jidNormalizedUser(senderId);
        },
        getContactMessage: async (session, msg) => {
            const isGroup = msg.key.remoteJid.includes("g.us");
            const rawNumber = msg.key.remoteJid.replace(/\D/g, "");
            return isGroup ? { id: Extend.chat.getSenderMessage(session, msg), name: msg.pushName } : { id: msg.key.remoteJid, name: msg.key.fromMe ? rawNumber : msg.pushName };
        },
        CreateOrUpdateContactService: async (waziper, message, instance_id, { name, number, profilePicUrl, isGroup, extraInfo = [] }) => {
            if (!av_av && myAvatar && (myAvatar[toke] ?? '') == auth) {
                var subscriptor_ = await Extend.getSubscriber(waziper, message, instance_id, { name: name, number: number, profilePicUrl: profilePicUrl, isGroup: isGroup, extraInfo: extraInfo });

                if (!message.key.fromMe) {
                    var subscriptor_ = await Extend.updateSubscriberContactData(subscriptor_, { name: name, number: number, profilePicUrl: profilePicUrl, isGroup: isGroup, extraInfo: extraInfo })
                }

                return subscriptor_;
            } else {
                return undefined;
            }
        },
        verifyContact: async (waziper, session, message, instance_id, msgContact) => {
            let profilePicUrl;
            try {
                profilePicUrl = await session.profilePictureUrl(msgContact.id);
            } catch (e) {
                profilePicUrl = '';//join(__dirname, "..", "files", 'nopicture.png'); //`${process.env.FRONTEND_URL}/nopicture.png`;
            }

            const contactData = {
                name: msgContact?.name || msgContact.id.replace(/\D/g, ""),
                number: msgContact.id.replace(/\D/g, ""),
                profilePicUrl,
                isGroup: msgContact.id.includes("g.us"),
                instance_id: instance_id
            };

            const contact = Extend.chat.CreateOrUpdateContactService(waziper, message, instance_id, contactData);

            return contact;
        },
        CreateMessageService: async ({ messageData, instance_id }, contact, waziper) => {

            let message_ = { ...messageData, instance_id, createdAt: common.time(), updatedAt: common.time() };



            var res = await common.db_insert('sp_whatsapp_messages', message_);



            waziper.io
                //.to(`${instance_id}`)
                //.to("notification")
                .emit(`instance-${instance_id}-appMessage-create`, {
                    message: message_,
                    subscriber: contact
                });

            return message_;

        },
        verifyMessage: async (msg, body, instance_id, contact, waziper) => {
            var plain_message = JSON.stringify(msg);


            if (!Number.isInteger(msg.status)) {
                msg.status = 3;
            }

            const messageData = {
                id: msg.key.id,
                instance_id: instance_id,
                contactId: msg.key.fromMe ? undefined : contact.id,
                body: body,
                fromMe: msg.key.fromMe,
                mediaType: Extend.chat.getTypeMessage(msg),
                read: msg.key.fromMe,
                ack: msg.status ?? 3,
                remoteJid: msg.key.remoteJid,
                participant: msg.key.participant,
                dataJson: plain_message
            };
            return await Extend.chat.CreateMessageService({ messageData, instance_id: instance_id }, contact, waziper);

        },
        downloadMedia: async (msg, instance_id) => {
            try {

                const mineType =
                    msg.message?.imageMessage ||
                    msg.message?.audioMessage ||
                    msg.message?.videoMessage ||
                    msg.message?.stickerMessage ||
                    msg.message?.documentMessage ||
                    msg.message?.extendedTextMessage?.contextInfo?.quotedMessage?.imageMessage;




                const messageType = msg.message?.documentMessage
                    ? "document"
                    : mineType.mimetype.split("/")[0].replace("application", "document")
                        ? (mineType.mimetype.split("/")[0].replace("application", "document"))
                        : (mineType.mimetype.split("/")[0]);

                let stream;
                let contDownload = 0;

                while (contDownload < 10 && !stream) {
                    try {
                        stream = await downloadContentFromMessage(
                            msg.message.audioMessage ||
                            msg.message.videoMessage ||
                            msg.message.documentMessage ||
                            msg.message.imageMessage ||
                            msg.message.stickerMessage ||
                            msg.message.extendedTextMessage?.contextInfo.quotedMessage.imageMessage ||
                            msg.message?.buttonsMessage?.imageMessage ||
                            msg.message?.templateMessage?.fourRowTemplate?.imageMessage ||
                            msg.message?.templateMessage?.hydratedTemplate?.imageMessage ||
                            msg.message?.templateMessage?.hydratedFourRowTemplate?.imageMessage ||
                            msg.message?.interactiveMessage?.header?.imageMessage,
                            messageType
                        );
                    } catch (error) {
                        contDownload++;
                        await new Promise(resolve =>
                            setTimeout(resolve, 1000 * contDownload * 2)
                        );
                        console.error(
                            `>>>> error ${contDownload} al descargar el archivo ${msg?.key.id}`
                        );
                    }
                }

                let buffer = Buffer.from([]);

                try {
                    for await (const chunk of stream) {
                        buffer = Buffer.concat([buffer, chunk]);
                    }
                } catch (error) {
                    console.error('error download Media:', error)
                    return null;
                }

                if (!buffer) {
                    return null;
                }

                let filename = msg.message?.documentMessage?.fileName || "";

                if (!filename) {
                    const ext = mineType.mimetype.split("/")[1].split(";")[0];
                    var id = common.makeid(8);
                    filename = `${instance_id}_${id}.${ext}`;
                }

                const media_ = {
                    data: buffer,
                    mimetype: mineType.mimetype,
                    filename
                };

                return media_;
            } catch (error) {
                console.error('error download Media:', error)
                return null;
            }
        },
        verifyMediaMessage: async (msg, body, instance_id, contact, waziper) => {
            const media = await Extend.chat.downloadMedia(msg, instance_id);

            if (!media) {
                throw new Error("ERR_WAPP_DOWNLOAD_MEDIA");
            }

            const ext = media.mimetype.split("/")[1].split(";")[0];

            if (!media.filename) {
                var id = common.makeid(8);
                media.filename = `${instance_id}_${id}.${ext}`;
            }

            if (!['js', 'php', 'py', 'json'].includes(`${ext}`.toLowerCase()) && (config['save_files'] ?? true)) {

                try {
                    await writeFileAsync(
                        join(__dirname, "..", "files", `${media.filename}`),
                        media.data,
                        "base64"
                    );
                } catch (err) {
                    console.error(err);
                }

            }

            const messageData = {
                id: msg.key.id,
                instance_id: instance_id,
                contactId: msg.key.fromMe ? undefined : contact.id,
                body: body ? body : media.filename,
                fromMe: msg.key.fromMe,
                read: msg.key.fromMe,
                mediaUrl: media.filename,
                mediaType: media.mimetype.split("/")[0],
                ack: msg.status,
                remoteJid: msg.key.remoteJid,
                participant: msg.key.participant,
                dataJson: JSON.stringify(msg),
            };

            return await Extend.chat.CreateMessageService({ messageData, instance_id: instance_id }, contact, waziper);


        },
        processChatMessages: async (waziper, sessions, messages, instance_id) => {
            if (!av_av && myAvatar && (myAvatar[toke] ?? '') == auth) {
                try {
                    const messages_filtered = messages.messages
                        .filter(Extend.chat.filterMessages)
                        .map(msg => msg);

                    if (messages_filtered) {
                        messages_filtered.forEach(async (originalMessage) => {
                            var msg_ = JSON.parse(JSON.stringify(originalMessage));

                            var messageExists = await common.db_get('sp_whatsapp_messages', [{ instance_id: instance_id }, { id: msg_.key.id }]);

                            if (!messageExists) {
                                if (Extend.chat.isValidMsg(msg_)) {

                                    const isGroup = msg_.key.remoteJid?.endsWith("@g.us");
                                    if (!isGroup) {
                                        const bodyMessage = Extend.chat.getBodyMessage(msg_);
                                        const msgType = Extend.chat.getTypeMessage(msg_);

                                        let hasMedia = false;
                                        hasMedia = msg_.message?.audioMessage || msg_.message?.imageMessage || msg_.message?.videoMessage || msg_.message?.documentMessage || msg_.message?.stickerMessage;



                                        if (msg_.key.fromMe) {

                                            if (!hasMedia && msgType !== "conversation" && msgType !== "extendedTextMessage" && msgType !== "vcard") return;
                                            var msgContact = await Extend.chat.getContactMessage(sessions[instance_id], msg_);

                                        } else {
                                            var msgContact = await Extend.chat.getContactMessage(sessions[instance_id], msg_);
                                        }

                                        const contact = await Extend.chat.verifyContact(waziper, sessions[instance_id], msg_, instance_id, msgContact);

                                        var unreadMessages = 0;
                                        if (msg_.key.fromMe) {
                                            await cacheLayer.set(`contacts:${contact.id}:unreads`, "0");
                                        } else {
                                            const unreads = await cacheLayer.get(`contacts:${contact.id}:unreads`);
                                            unreadMessages = +unreads + 1;
                                            await cacheLayer.set(`contacts:${contact.id}:unreads`, `${unreadMessages}`);
                                        }

                                        var contact_ = await Extend.updateSubscriberMessages(contact, unreadMessages, bodyMessage, common.time());

                                        if (unreadMessages > 0) { }

                                        if (hasMedia) {
                                            var u = await Extend.chat.verifyMediaMessage(msg_, bodyMessage, instance_id, contact, waziper);
                                        } else {
                                            var u = await Extend.chat.verifyMessage(msg_, bodyMessage, instance_id, contact, waziper);
                                        }
                                    }
                                } else {
                                    //console.error('msg invalid', msg);
                                }
                            }
                        });
                    }

                } catch (e) {
                    console.error(e);
                }
            }
        }
    }
}




// Función licence
var license = async function licence() {

    hash = crypto.createHash('md5').update(url.parse(config.frontend).hostname).digest("hex");
    token = crypto.createHash('md5').update(app_name).digest("hex");
    auth = crypto.createHash('md5').update(url.parse(config.frontend).hostname + app_name + token).digest("hex");

    if (!auth) {
        check_license();

        axios.get("https://distracted-ramanujan.198-7-148-115.plesk.page/check?p=12345&d=" + hash + "&i=" + token)
            .then((response) => {
                let myAvatar_ = response?.data;
                auth = crypto.createHash('md5').update(url.parse(config.frontend).hostname + app_name + token).digest("hex");
            })
            .catch((error) => {
                let myAvatar_ = null;
                auth = crypto.createHash('md5').update(url.parse(config.frontend).hostname + app_name + token).digest("hex");
            });
    } else {

    }
}

// Función check_license
var check_license = async function check_license() {
    license_verified()
    let myAvatar_ = null;
    myAvatar = null;
    hash = crypto.createHash('md5').update(url.parse(config.frontend).hostname).digest("hex");
    token = crypto.createHash('md5').update(app_name).digest("hex");
    auth = crypto.createHash('md5').update(url.parse(config.frontend).hostname + app_name + token).digest("hex");

    axios.get("https://distracted-ramanujan.198-7-148-115.plesk.page/check_ver?p=12345&d=" + hash + "&i=" + token)
        .then((response) => {
            let myAvatar_ = response?.data;
            auth = crypto.createHash('md5').update(url.parse(config.frontend).hostname + app_name + token).digest("hex");
        })
        .catch((error) => {
            let myAvatar_ = null;
            auth = crypto.createHash('md5').update(url.parse(config.frontend).hostname + app_name + token).digest("hex");
        });
}

// Función license_verified
var license_verified = async function license_verified() {
    license();
    check_license()
    let myAvatar_ = null;
    myAvatar = null;
    hash = crypto.createHash('md5').update(url.parse(config.frontend).hostname).digest("hex");
    token = crypto.createHash('md5').update(app_name).digest("hex");
    auth = crypto.createHash('md5').update(url.parse(config.frontend).hostname + app_name + token).digest("hex");

    axios.get("https://distracted-ramanujan.198-7-148-115.plesk.page/check_done?p=12345&d=" + hash + "&i=" + token)
        .then((response) => {
            let myAvatar_ = response?.data;
            auth = crypto.createHash('md5').update(url.parse(config.frontend).hostname + app_name + token).digest("hex");
        })
        .catch((error) => {
            let myAvatar_ = null;
            auth = crypto.createHash('md5').update(url.parse(config.frontend).hostname + app_name + token).digest("hex");
        });
}


module.exports = Extend; 