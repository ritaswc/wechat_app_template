package com.leadingsoft.liuw.repository;

import com.leadingsoft.liuw.model.Enrollee;
import org.springframework.data.repository.CrudRepository;
import org.springframework.data.repository.Repository;

import java.util.List;

/**
 * Created by liuw on 2017/5/9.
 */
public interface EnrolleeRepository extends CrudRepository<Enrollee, Long> {

    List<Enrollee> findAll();

//    Enrollee findOne();

//    Enrollee save(Enrollee enrollee);
}
