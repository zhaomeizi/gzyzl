<?php
if (! function_exists ( 'cal_days_in_month' )) {
	function cal_days_in_month($calendar, $month, $year) {
		return date ( 't', mktime ( 0, 0, 0, $month, 1, $year ) );
	}
}
if (! defined ( 'CAL_GREGORIAN' ))
	define ( 'CAL_GREGORIAN', 1 );


//返回主页
function goHome(){
    header('Location:/');
    exit();
}

// 生成GUID
function createGuid() {
    $charid = strtoupper ( md5 ( uniqid ( mt_rand (), true ) ) );
    $hyphen = chr ( 45 ); // "-"
    // $uuid = chr(123) // "{"
    $uuid = '' . substr ( $charid, 0, 8 ) . $hyphen . substr ( $charid, 8, 4 ) . $hyphen . substr ( $charid, 12, 4 ) . $hyphen . substr ( $charid, 16, 4 ) . $hyphen . substr ( $charid, 20, 12 );
    // .chr(125);// "}"
    return $uuid;
}

/**
 * 发送HTTP请求方法，目前只支持CURL发送请求
 * @param string $url 请求URL
 * @param array $data POST的数据，GET请求时该参数无效
 * @param string $method 请求方法GET/POST
 * @param string $urldecode  是否进行urldecode 才返回 默认不decode
 * @return array 响应数据
 */
function appCurlSend($url, $data = '', $method = 'GET',$urldecode = false){
    $opts = array(
            CURLOPT_RETURNTRANSFER => 1,
       //     CURLOPT_SSL_VERIFYPEER => false,
      //      CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_CONNECTTIMEOUT => 15,          // timeout on connect
            CURLOPT_TIMEOUT        => 15,          // timeout on response
    );

    //根据请求类型设置特定参数
    if(strtoupper($method) == 'GET'){
        if(is_array($data)){
            $opts[CURLOPT_URL] = $url . '?' . http_build_query($data);
        }else{
            if($data != ''){
                $opts[CURLOPT_URL] = $url . '?' . $data;
            }else{
                $opts[CURLOPT_URL] = $url;
            }
        }
    }
    if(strtoupper($method) == 'POST'){
        $opts[CURLOPT_URL] = $url;
        $opts[CURLOPT_POST] = 1;
        if(is_array($data)){
         //   $opts[CURLOPT_POSTFIELDS] = http_build_query($data);
            $opts[CURLOPT_POSTFIELDS] = $data;
        }
        if(is_string($data)){
            $opts[CURLOPT_HTTPHEADER] = array(
                    'Content-Type: application/json; charset=utf-8',
                    'Content-Length: ' . strlen($data),
            );
        }
    }

    //初始化并执行curl请求
    $ch = curl_init();
    curl_setopt_array($ch, $opts);
    $data = curl_exec($ch);
    $error = curl_error($ch);
    curl_close($ch);

    //发生错误，抛出异常
    if($data == false){
        return $error;
    }
    if($urldecode){
        return urldecode($data);
    }else{
        return $data;
    }
}//end curl send
