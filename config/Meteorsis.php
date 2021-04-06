<?php

return [
    'limit_per_request' => 20,
    'functions' => [
        'send_sms' => [
            'url'=>'https://www.meteorsis.com/misweb/f_sendsms.aspx',
            'query'=>['username','password','content','langeng','recipient','dos','senderid']
        ],
        'get_sms_status' => [
            'url'=>'https://www.meteorsis.com/misweb/f_getsmsdstatus.aspx',
            'query'=>['username','password','smsdid']
        ],
        'get_account_balance' => [
            'url'=>'https://www.meteorsis.com/misweb/f_getuserbalance.aspx',
            'query'=>['username','password'],
        ],
        'get_server_query' => [
            'url'=>'https://www.meteorsis.com/misweb/f_getserverqueue.aspx',
            'query'=>['username','password'],
        ],
        'callback' => [
            'url'=>'/receivedr.php',
            'query'=>['smsdid','recipient','senderid','content','dos','smsdstatus','smsderrorcode','charged'],
        ],
    ],
    'api_err_code' => [
        1 => '非預期系統錯誤',
        2 => '用戶名稱與密碼不符',
        3 => '戶口己被停用',
        5 => '短訊的排程日期只限於 5 年之內',
        6 => '短訊內容必須存在，同時英文短訊的字數只限於 459；或統一碼短訊的字數只限於 201',
        8 => '顯示名稱必須存在，並只限於 11 個位的英文及數字組合或 16 個位的數字',
        9 => 'SMSD ID 不存在',
        16 => '餘額不足',
        17 => '電話號碼格式錯誤',
        18 => '中國短訊的內容包含非法詞語',
        20 => '連接逾時',
        240 => '網址格式錯誤：參數數目不符',
        241 => '網址格式錯誤：content',
        242 => '網址格式錯誤：langeng',
        243 => '網址格式錯誤：recipient',
        244 => '網址格式錯誤：dos',
        245 => '網址格式錯誤：senderid',
        246 => '網址格式錯誤：smsdid',
        248 => '網址格式錯誤：dncr',
        249 => '網址格式錯誤：URL 包含危險性的字元',
    ],
    'sms_status' => [
        1 => ['status'=>'上傳失敗','desc'=>'短訊不能傳送至本公司的短訊系統'],
        2 => ['status'=>'上傳成功','desc'=>'短訊成功傳送至本公司的短訊系統'],
        3 => ['status'=>'排程','desc'=>'短訊暫存在本公司的系統，並於指定的日期發送'],
        4 => ['status'=>'處理','desc'=>'正在發送短訊到有關的電訊供應商'],
        19 => ['status'=>'重發','desc'=>'短訊已傳送到電訊供應商處理，但仍未到達電話。可能電話關掉，號碼飛線或短訊收件匣已滿。於 1 – 23 小時的有效期內供應商仍會不斷重發'],
        20 => ['status'=>'過期','desc'=>'短訊已傳送到電訊供應商處理，但仍未到達電話。可能電話關掉，號碼飛線或短訊收件匣已滿。於 1 – 23 小時的有效期內供應商仍會不斷重發。有效期後，若傳送仍然失敗，短訊被取消'],
        21 => ['status'=>'無法傳送','desc'=>'不能傳送短訊到這個號碼，可能號碼沒有登記，或系統不支援香港地區以外的號碼，或號碼登記人要求電訊供應商拒絕接收滋擾性的宣傳等等'],
        22 => ['status'=>'確認傳送','desc'=>'短訊已傳送到電話，並且回傳收條到所屬電訊供應商，再由供應商轉發至本公司的短訊系統確認到達'],        
    ],
    'sms_status_err_code' => [
        300 => '不適用',
        301 => '回條未接收',
        302 => '號碼格式錯誤',
        303 => '號碼不存在、己停用',
        304 => '手機已關、接收不良、飛線',
        305 => '短訊匣已滿',
        306 => '不支援當地網絡',
        307 => '拒收名冊號碼',
        308 => '餘額不足',
        309 => '中國短訊非法詞語',
        312 => '訊息被封鎖，例如客戶欠款、濫發訊息、客戶拒收、程式攔截',
        315 => '本地電訊商系統故障',
        326 => '短訊收費超出上限',
    ],
    'service_country' => [
        '*' //'zh-cn','zh-hk','zh-mo','zh-tw',
    ],
    'max_words' => [
        'title' => [11,16],     //英文+數字, 數字
        'content' => [201,459], //Unicode-16, 英文+數字+符號
    ],
];
