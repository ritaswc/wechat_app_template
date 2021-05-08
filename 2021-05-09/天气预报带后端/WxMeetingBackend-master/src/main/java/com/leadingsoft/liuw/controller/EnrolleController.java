package com.leadingsoft.liuw.controller;

import com.leadingsoft.liuw.model.Enrollee;
import com.leadingsoft.liuw.repository.EnrolleeRepository;
import lombok.extern.slf4j.Slf4j;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RestController;

/**
 * Created by liuw on 2017/5/9.
 */
@Slf4j
@RestController
@RequestMapping("/w/enrollees")
public class EnrolleController {

    @Autowired
    private EnrolleeRepository enrolleeRepository;

    @RequestMapping("/test")
    public void test(){
        Enrollee enrollee = new Enrollee();
        enrollee.setName("Liuw");
        enrollee.setWxId("xxx");

        this.enrolleeRepository.save(enrollee);
    }
}
