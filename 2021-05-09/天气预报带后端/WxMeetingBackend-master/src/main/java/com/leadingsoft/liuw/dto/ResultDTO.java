package com.leadingsoft.liuw.dto;

/**
 * Created by liuw on 2017/4/21.
 */

import com.fasterxml.jackson.annotation.JsonIgnore;
import com.fasterxml.jackson.annotation.JsonInclude;
import com.fasterxml.jackson.annotation.JsonProperty;
import org.springframework.data.domain.Page;

import java.util.Date;
import java.util.List;

/**
 * 返回结果DTO
 */
public class ResultDTO<T> {
    /**
     * 请求的处理结果状态
     */
    public enum Status {
        /**
         * 成功 或 失败
         */
        success, failure;
    }

    /**
     * 处理结果状态
     */
    private Status status;

    /**
     * 最新数据的时间戳（可选）
     */
    private Date timestamp;

    /**
     * 异常消息
     */
    private Error[] errors;

    /**
     * 结果数据
     */
    private T data;

    private PageData pageable;

    @JsonIgnore
    public boolean isSuccess() {
        return this.status == Status.success;
    }

    @JsonIgnore
    public boolean isFailure() {
        return this.status == Status.failure;
    }

    @JsonProperty(value = "status", index = 0)
    public Status getStatus() {
        return this.status;
    }

    @JsonIgnore
    public void setStatus(final Status status) {
        this.status = status;
    }

    @JsonInclude(JsonInclude.Include.NON_NULL)
    @JsonProperty(value = "timestamp", index = 1)
    public Date getTimestamp() {
        return this.timestamp;
    }

    @JsonIgnore
    public void setTimestamp(final Date timestamp) {
        this.timestamp = timestamp;
    }

    @JsonInclude(JsonInclude.Include.NON_NULL)
    @JsonProperty(value = "errors", index = 2)
    public Error[] getErrors() {
        return this.errors;
    }

    @JsonIgnore
    public void setErrors(final Error... errors) {
        this.errors = errors;
    }

    @JsonInclude(JsonInclude.Include.NON_NULL)
    @JsonProperty(value = "data", index = 3)
    public T getData() {
        return this.data;
    }

    @JsonIgnore
    public void setData(final T data) {
        this.data = data;
    }

    @JsonInclude(JsonInclude.Include.NON_NULL)
    @JsonProperty(value = "pageable", index = 4)
    public PageData getPageable() {
        return this.pageable;
    }

    @JsonIgnore
    public void setPageable(final PageData pageable) {
        this.pageable = pageable;
    }

    public ResultDTO() {
    }

    public static ResultDTO<Void> success() {
        final ResultDTO<Void> result = new ResultDTO<>();
        result.setStatus(Status.success);
        return result;
    }

    public static <T> ResultDTO<T> success(final T data) {
        final ResultDTO<T> result = new ResultDTO<T>();
        result.setStatus(Status.success);
        result.setData(data);
        return result;
    }

    public static <T> ResultDTO<List<T>> success(final Page<T> pageData) {
        final ResultDTO<List<T>> result = new ResultDTO<>();
        result.setStatus(Status.success);
        result.setData(pageData.getContent());
        result.setPageable(PageData.convert(pageData));
        return result;
    }

    public static ResultDTO<Void> failure(final Error... errors) {
        final ResultDTO<Void> result = new ResultDTO<>();
        result.setStatus(Status.failure);
        result.setErrors(errors);
        return result;
    }

    public static <T> ResultDTO<T> failure(final T data, final Error... errors) {
        final ResultDTO<T> result = new ResultDTO<>();
        result.setStatus(Status.failure);
        result.setData(data);
        result.setErrors(errors);
        return result;
    }
}