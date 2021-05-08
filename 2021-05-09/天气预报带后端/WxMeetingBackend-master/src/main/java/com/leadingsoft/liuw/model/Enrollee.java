package com.leadingsoft.liuw.model;

import lombok.Getter;
import lombok.Setter;
import org.springframework.data.annotation.Id;
import org.springframework.data.jpa.domain.AbstractPersistable;

import javax.persistence.Entity;


/**
 * Created by liuw on 2017/5/8.
 */
@Getter
@Setter
@Entity
public class Enrollee extends AbstractPersistable<Long> {

    private String name;

    private String wxId;
}
