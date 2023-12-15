const config = require("./config.js");
const Common = require("./waziper/common.js");
const WAZIPER = require("./waziper/waziper.js");
const express = require('express');
const path = require('path')

//WAZIPER.app.use('/files', express.static('files'))
WAZIPER.app.use('/files',  express.static(path.join(__dirname, 'files')));

WAZIPER.app.get('/instance', WAZIPER.cors, async (req, res) => {
    var access_token = req.query.access_token;
    var instance_id = req.query.instance_id;

    await WAZIPER.instance(access_token, instance_id, false, res, async (client) => {
        await WAZIPER.get_info(instance_id, res);
    });
});

WAZIPER.app.get('/get_qrcode', WAZIPER.cors, async (req, res) => {
    var access_token = req.query.access_token;
    var instance_id = req.query.instance_id;

    await WAZIPER.instance(access_token, instance_id, true, res, async (client) => {
        await WAZIPER.get_qrcode(instance_id, res);
    });
});

WAZIPER.app.get('/get_groups', WAZIPER.cors, async (req, res) => {
    var access_token = req.query.access_token;
    var instance_id = req.query.instance_id;

    await WAZIPER.instance(access_token, instance_id, false, res, async (client) => {
        await WAZIPER.get_groups(instance_id, res);
    });
});

WAZIPER.app.get('/logout', WAZIPER.cors, async (req, res) => {
    var access_token = req.query.access_token;
    var instance_id = req.query.instance_id;
    WAZIPER.logout(instance_id, res);
});

WAZIPER.app.post('/send_message', WAZIPER.cors, async (req, res) => {
    var access_token = req.query.access_token;
    var instance_id = req.query.instance_id;

    await WAZIPER.instance(access_token, instance_id, false, res, async (client) => {
        WAZIPER.send_message(instance_id, access_token, req, res);
    });
});

WAZIPER.app.get('/reset', WAZIPER.cors, async function (req, res, next) {
    var api_key = await Common.db_query(`select value from sp_options where name = 'admin_api_key'`, true);
    if (api_key) {
        if (req.query.api_key == api_key.value) {
            res.json({
                status: 'success',
                message: 'Success'
            });

            process.exit();
        } else {
            res.json({
                status: 'error',
                message: 'not allowed',
                api: api_key
            });
        }
    }

});

WAZIPER.app.get('/', WAZIPER.cors, async (req, res) => {
    return res.json({ status: 'success', message: "Welcome to WAZIPER" });
});

WAZIPER.server.listen(config.port, async () => {
    console.log("WAZIPER IS LIVE, DEMO VERSION, PLEASE BUY THE ORIGINAL SCRIPT");
});