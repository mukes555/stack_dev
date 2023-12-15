<?php 
if(!function_exists("FB_ANALYTICS_DATA")){
    function FB_ANALYTICS_DATA($result){  
        $columns="";
        $stats="";

        if(!empty($result)){
            $data = $result;
            $items=[];
            foreach ($data as $row) {
                $columns .= "'".date("M j", strtotime($row["end_time"]))."',";
                $stats .= $row["value"].",";
                $items[] = $row["value"];
            }
        }

        return [
            "columns" => "[".substr($columns, 0, -1)."]",
            "stats" => "[".substr($stats, 0, -1)."]",
            "data" => $items
        ];
    }
}

if(!function_exists("FB_ANALYTICS_DATA_SUB")){
    function FB_ANALYTICS_DATA_SUB($result, $sub){  
        $columns="";
        $stats="";
        $count=0;
        $items=[];
        if(!empty($result)){
            $data = $result;
            foreach ($data as $row) {
                $columns .= "'".date("M j", strtotime($row["end_time"]))."',";
                if(!empty($row["value"]) && isset($row["value"][$sub])){
                    $count += $row["value"][$sub];
                    $stats .= $row["value"][$sub].",";
                    $items[] = $row["value"][$sub];
                }else{
                    $stats .= "0,";
                    $items[] = 0;
                }
            }
        }

        return [
            "columns" => "[".substr($columns, 0, -1)."]",
            "stats" => "[".substr($stats, 0, -1)."]",
            "count" => $count,
            "data" => $items
        ];
    }
}

if(!function_exists("FB_ANALYTICS_DATA_SUB_PLUS")){
    function FB_ANALYTICS_DATA_SUB_PLUS($result){  
        $columns="";
        $stats="";
        $count=0;
        $items=[];
        if(!empty($result)){
            $data = $result;
            foreach ($data as $row) {
                $columns .= "'".date("M j", strtotime($row["end_time"]))."',";
                if(!empty($row["value"])){
                    $sum = array_sum($row["value"]);
                    $count += $sum;
                    $stats .= $sum.",";
                    $items[] = $sum;
                }else{
                    $stats .= "0,";
                    $items[] = 0;
                }
            }
        }

        return [
            "columns" => "[".substr($columns, 0, -1)."]",
            "stats" => "[".substr($stats, 0, -1)."]",
            "count" => $count,
            "data" => $items
        ];
    }
}

if(!function_exists("FB_ANALYTICS_ENGAGEMENT_RATE")){
    function FB_ANALYTICS_ENGAGEMENT_RATE($impressions, $engagements){ 
        $stats = "";
        $rate = 0;
        $engagements_data = [];
        $engagements_rate = [];
        foreach ($engagements as $key => $engagement) {
            foreach ($engagement as $key => $value) {
                if(!isset($engagements_data[$key])){
                    $engagements_data[] = $value;
                }else{
                    $engagements_data[$key] += $value;
                }
            }
        }

        if(!empty($engagements_data)){
            foreach ($engagements_data as $key => $value) {
                if($impressions['data'][$key] > 0){
                    $stats .= round($value/$impressions['data'][$key]*100, 2).",";
                    $engagements_rate[]= round($value/$impressions['data'][$key]*100, 2);
                }else{
                    $stats .= "0,";
                    $engagements_rate[]= 0;
                }
            }
        }

        if(array_sum($impressions['data']) != 0){
            $rate = round( array_sum($engagements_data)/array_sum($impressions['data'])*100, 2);
        }

        return [
            "columns" => $impressions['columns'],
            "stats" => "[".substr($stats, 0, -1)."]",
            "data" => $engagements_rate,
            "rate" => $rate,
        ];
    }
}

if(!function_exists("FB_ANALYTICS_DATA_TYPE")){
    function FB_ANALYTICS_DATA_TYPE($result, $type = ""){  
        $columns = "";
    	$stats = "";
        foreach ($result as $key => $value) {
            switch ($type) {
                case 'country':
                    $columns .= "'".FB_COUNTRY($key)."',";
                    break;
                
                default:
                    $columns .= "'".$key."',";
                    break;
            }
            
            $stats .= $value.",";
        }

        return [
            "columns" => "[".substr($columns, 0, -1)."]",
            "stats" => "[".substr($stats, 0, -1)."]"
        ];
    }
}

if(!function_exists("FB_ANALYTICS_DATA_MAP")){
    function FB_ANALYTICS_DATA_MAP($result, $type = ""){  
        $stats = "";
        foreach ($result as $key => $value) {
            $stats.="{'code':'".$key."','value':".$value."},";
        }
        return "[".substr($stats, 0, -1)."]";
    }
}

if(!function_exists("FB_ANALYTICS_FANS_GENDER_AGE")){
    function FB_ANALYTICS_FANS_GENDER_AGE($result){  
        $columns = "";
        $male = "";
        $female = "";
        $total_male = 0;
        $total_female = 0;
        $percent_male = 0;
        $percent_female = 0;
        foreach ($result as $key => $value) {
            $columns .= "'".$key."',";
            $male .= $value[0].",";
            $female .= $value[1].",";

            $total_male += $value[0];
            $total_female += $value[1];
        }

        if($total_male != 0 || $total_female != 0){
            $percent_male = round( $total_male/($total_male + $total_female)*100 );
            $percent_female = round( $total_female/($total_male + $total_female)*100 );;
        }

        return [
            "columns" => "[".substr($columns, 0, -1)."]",
            "male" => "[".substr($male, 0, -1)."]",
            "female" => "[".substr($female, 0, -1)."]",
            "total_male" => $total_male,
            "total_female" => $total_female,
            "percent_male" => $percent_male,
            "percent_female" => $percent_female
        ];
    }
}

if(!function_exists("FB_ANALYTICS_GAINED_AND_LOST_FANS")){
    function FB_ANALYTICS_GAINED_AND_LOST_FANS($gained_fans, $lost_fans){  
        $columns = "";
        $gained_fans_stats = "";
        $lost_fans_stats = "";
        $net_fans_stats = "";
        $gained_fans_arr = [];
        $lost_fans_arr = [];

        if(!empty($gained_fans)){
            $data = $gained_fans;
            foreach ($data as $row) {
                $date = date("Y-m-d H:i:s", strtotime($row["end_time"]));
                $year  = date("Y", strtotime($date));
                $month = date("n", strtotime($date)) - 1;
                $day   = date("d", strtotime($date));
                $columns .= "'".date("M j", strtotime($row["end_time"]))."',";
                $gained_fans_stats .= $row["value"].",";
                $gained_fans_arr[] = $row["value"];
            }
        }else{
            $columns = "[]";
            $gained_fans_stats = "[]";
        } 

        if(!empty($lost_fans)){
            $data = $lost_fans;
            foreach ($data as $row) {
                $lost_fans_stats .= $row["value"].",";
                $lost_fans_arr[] = $row["value"];
            }
        }else{
            $columns = "[]";
            $lost_fans_stats = "[]";
        }

        if(!empty($gained_fans_arr) && 
            !empty($lost_fans_arr) && 
            count($lost_fans_arr) == count($gained_fans_arr) 
        ){
            foreach ($gained_fans_arr as $key => $value) {
                $net_fans_stats .= ($value+$lost_fans_arr[$key]).",";
            }
        }else{
            $net_fans_stats = "[]";
        }

        return [
            "columns" => "[".substr($columns, 0, -1)."]",
            "gained_fans" => "[".substr($gained_fans_stats, 0, -1)."]",
            "lost_fans" => "[".substr($lost_fans_stats, 0, -1)."]",
            "net_fans" => "[".substr($net_fans_stats, 0, -1)."]",
        ];

    }
}

if(!function_exists("FB_COUNTRY")){
    function FB_COUNTRY($key){
        $data = array(
            'AF' => 'Afghanistan',
            'AX' => 'Aland Islands',
            'AL' => 'Albania',
            'DZ' => 'Algeria',
            'AS' => 'American Samoa',
            'AD' => 'Andorra',
            'AO' => 'Angola',
            'AI' => 'Anguilla',
            'AQ' => 'Antarctica',
            'AG' => 'Antigua and Barbuda',
            'AR' => 'Argentina',
            'AM' => 'Armenia',
            'AW' => 'Aruba',
            'AU' => 'Australia',
            'AT' => 'Austria',
            'AZ' => 'Azerbaijan',
            'BS' => 'Bahamas the',
            'BH' => 'Bahrain',
            'BD' => 'Bangladesh',
            'BB' => 'Barbados',
            'BY' => 'Belarus',
            'BE' => 'Belgium',
            'BZ' => 'Belize',
            'BJ' => 'Benin',
            'BM' => 'Bermuda',
            'BT' => 'Bhutan',
            'BO' => 'Bolivia',
            'BA' => 'Bosnia and Herzegovina',
            'BW' => 'Botswana',
            'BV' => 'Bouvet Island (Bouvetoya)',
            'BR' => 'Brazil',
            'IO' => 'British Indian Ocean Territory',
            'VG' => 'British Virgin Islands',
            'BN' => 'Brunei Darussalam',
            'BG' => 'Bulgaria',
            'BF' => 'Burkina Faso',
            'BI' => 'Burundi',
            'KH' => 'Cambodia',
            'CM' => 'Cameroon',
            'CA' => 'Canada',
            'CV' => 'Cape Verde',
            'KY' => 'Cayman Islands',
            'CF' => 'Central African Republic',
            'TD' => 'Chad',
            'CL' => 'Chile',
            'CN' => 'China',
            'CX' => 'Christmas Island',
            'CC' => 'Cocos (Keeling) Islands',
            'CO' => 'Colombia',
            'KM' => 'Comoros the',
            'CD' => 'Congo',
            'CG' => 'Congo the',
            'CK' => 'Cook Islands',
            'CR' => 'Costa Rica',
            'CI' => 'Cote d`Ivoire',
            'HR' => 'Croatia',
            'CU' => 'Cuba',
            'CY' => 'Cyprus',
            'CZ' => 'Czech Republic',
            'DK' => 'Denmark',
            'DJ' => 'Djibouti',
            'DM' => 'Dominica',
            'DO' => 'Dominican Republic',
            'EC' => 'Ecuador',
            'EG' => 'Egypt',
            'SV' => 'El Salvador',
            'GQ' => 'Equatorial Guinea',
            'ER' => 'Eritrea',
            'EE' => 'Estonia',
            'ET' => 'Ethiopia',
            'FO' => 'Faroe Islands',
            'FK' => 'Falkland Islands (Malvinas)',
            'FJ' => 'Fiji the Fiji Islands',
            'FI' => 'Finland',
            'FR' => 'France',
            'GF' => 'French Guiana',
            'PF' => 'French Polynesia',
            'TF' => 'French Southern Territories',
            'GA' => 'Gabon',
            'GM' => 'Gambia the',
            'GE' => 'Georgia',
            'DE' => 'Germany',
            'GH' => 'Ghana',
            'GI' => 'Gibraltar',
            'GR' => 'Greece',
            'GL' => 'Greenland',
            'GD' => 'Grenada',
            'GP' => 'Guadeloupe',
            'GU' => 'Guam',
            'GT' => 'Guatemala',
            'GG' => 'Guernsey',
            'GN' => 'Guinea',
            'GW' => 'Guinea-Bissau',
            'GY' => 'Guyana',
            'HT' => 'Haiti',
            'HM' => 'Heard Island and McDonald Islands',
            'VA' => 'Holy See',
            'HN' => 'Honduras',
            'HK' => 'Hong Kong',
            'HU' => 'Hungary',
            'IS' => 'Iceland',
            'IN' => 'India',
            'ID' => 'Indonesia',
            'IR' => 'Iran',
            'IQ' => 'Iraq',
            'IE' => 'Ireland',
            'IM' => 'Isle of Man',
            'IL' => 'Israel',
            'IT' => 'Italy',
            'JM' => 'Jamaica',
            'JP' => 'Japan',
            'JE' => 'Jersey',
            'JO' => 'Jordan',
            'KZ' => 'Kazakhstan',
            'KE' => 'Kenya',
            'KI' => 'Kiribati',
            'KP' => 'Korea',
            'KR' => 'Korea',
            'KW' => 'Kuwait',
            'KG' => 'Kyrgyz Republic',
            'LA' => 'Lao',
            'LV' => 'Latvia',
            'LB' => 'Lebanon',
            'LS' => 'Lesotho',
            'LR' => 'Liberia',
            'LY' => 'Libyan Arab Jamahiriya',
            'LI' => 'Liechtenstein',
            'LT' => 'Lithuania',
            'LU' => 'Luxembourg',
            'MO' => 'Macao',
            'MK' => 'Macedonia',
            'MG' => 'Madagascar',
            'MW' => 'Malawi',
            'MY' => 'Malaysia',
            'MV' => 'Maldives',
            'ML' => 'Mali',
            'MT' => 'Malta',
            'MH' => 'Marshall Islands',
            'MQ' => 'Martinique',
            'MR' => 'Mauritania',
            'MU' => 'Mauritius',
            'YT' => 'Mayotte',
            'MX' => 'Mexico',
            'FM' => 'Micronesia',
            'MD' => 'Moldova',
            'MC' => 'Monaco',
            'MN' => 'Mongolia',
            'ME' => 'Montenegro',
            'MS' => 'Montserrat',
            'MA' => 'Morocco',
            'MZ' => 'Mozambique',
            'MM' => 'Myanmar',
            'NA' => 'Namibia',
            'NR' => 'Nauru',
            'NP' => 'Nepal',
            'AN' => 'Netherlands Antilles',
            'NL' => 'Netherlands the',
            'NC' => 'New Caledonia',
            'NZ' => 'New Zealand',
            'NI' => 'Nicaragua',
            'NE' => 'Niger',
            'NG' => 'Nigeria',
            'NU' => 'Niue',
            'NF' => 'Norfolk Island',
            'MP' => 'Northern Mariana Islands',
            'NO' => 'Norway',
            'OM' => 'Oman',
            'PK' => 'Pakistan',
            'PW' => 'Palau',
            'PS' => 'Palestinian Territory',
            'PA' => 'Panama',
            'PG' => 'Papua New Guinea',
            'PY' => 'Paraguay',
            'PE' => 'Peru',
            'PH' => 'Philippines',
            'PN' => 'Pitcairn Islands',
            'PL' => 'Poland',
            'PT' => 'Portugal => Portuguese Republic',
            'PR' => 'Puerto Rico',
            'QA' => 'Qatar',
            'RE' => 'Reunion',
            'RO' => 'Romania',
            'RU' => 'Russian Federation',
            'RW' => 'Rwanda',
            'BL' => 'Saint Barthelemy',
            'SH' => 'Saint Helena',
            'KN' => 'Saint Kitts and Nevis',
            'LC' => 'Saint Lucia',
            'MF' => 'Saint Martin',
            'PM' => 'Saint Pierre and Miquelon',
            'VC' => 'Saint Vincent and the Grenadines',
            'WS' => 'Samoa',
            'SM' => 'San Marino',
            'ST' => 'Sao Tome and Principe',
            'SA' => 'Saudi Arabia',
            'SN' => 'Senegal',
            'RS' => 'Serbia',
            'SC' => 'Seychelles',
            'SL' => 'Sierra Leone',
            'SG' => 'Singapore',
            'SK' => 'Slovakia',
            'SI' => 'Slovenia',
            'SB' => 'Solomon Islands',
            'SO' => 'Somalia',
            'ZA' => 'South Africa',
            'GS' => 'South Georgia and the South Sandwich Islands',
            'ES' => 'Spain',
            'LK' => 'Sri Lanka',
            'SD' => 'Sudan',
            'SR' => 'Suriname',
            'SJ' => 'Svalbard & Jan Mayen Islands',
            'SZ' => 'Swaziland',
            'SE' => 'Sweden',
            'CH' => 'Switzerland => Swiss Confederation',
            'SY' => 'Syrian Arab Republic',
            'TW' => 'Taiwan',
            'TJ' => 'Tajikistan',
            'TZ' => 'Tanzania',
            'TH' => 'Thailand',
            'TL' => 'Timor-Leste',
            'TG' => 'Togo',
            'TK' => 'Tokelau',
            'TO' => 'Tonga',
            'TT' => 'Trinidad and Tobago',
            'TN' => 'Tunisia',
            'TR' => 'Turkey',
            'TM' => 'Turkmenistan',
            'TC' => 'Turks and Caicos Islands',
            'TV' => 'Tuvalu',
            'UG' => 'Uganda',
            'UA' => 'Ukraine',
            'AE' => 'United Arab Emirates',
            'GB' => 'United Kingdom',
            'US' => 'United States',
            'UM' => 'United States Minor Outlying Islands',
            'VI' => 'United States Virgin Islands',
            'UY' => 'Uruguay, Eastern Republic of',
            'UZ' => 'Uzbekistan',
            'VU' => 'Vanuatu',
            'VE' => 'Venezuela',
            'VN' => 'Vietnam',
            'WF' => 'Wallis and Futuna',
            'EH' => 'Western Sahara',
            'YE' => 'Yemen',
            'ZM' => 'Zambia',
            'ZW' => 'Zimbabwe'
        );

        if(!empty($data[$key])){
            return $data[$key];
        }else{
            return $key;
        }
    }
}