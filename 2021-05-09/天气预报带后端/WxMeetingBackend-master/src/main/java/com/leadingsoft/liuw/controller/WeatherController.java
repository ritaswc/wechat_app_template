package com.leadingsoft.liuw.controller;

import lombok.extern.slf4j.Slf4j;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RequestMethod;
import org.springframework.web.bind.annotation.RestController;

import java.io.BufferedReader;
import java.io.DataOutputStream;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.net.HttpURLConnection;
import java.net.URL;

/**
 * Created by liuw on 2017/4/19.
 */
@Slf4j
@RestController
@RequestMapping("/w/weather")
public class WeatherController {

    @RequestMapping(method = RequestMethod.GET)
    public String get(){
        String result = "";
        try {
            URL url = new URL("http://api.openweathermap.org/data/2.5/weather?q=Dalian&appid=dafef1a9be486b5015eb92330a0add7d");
            HttpURLConnection connection = (HttpURLConnection)url.openConnection();
            connection.setDoInput(true);
            connection.setDoOutput(true);
            connection.setRequestMethod("GET");
            connection.setUseCaches(false);
            connection.setInstanceFollowRedirects(true);
            connection.connect();
            InputStream in = connection.getInputStream();

            BufferedReader read = new BufferedReader(new InputStreamReader(in, "UTF-8"));

            String line="";
            int i=0;
            while ( (line=read.readLine())!= null ) {
                result += line;
                i++;
            }
            // 断开连接
            read.close();
            in.close();
            connection.disconnect();
        } catch (Exception e) {
            WeatherController.log.error("取得天气数据失败", e);
        }

        return result;
    }
}
