package com.leadingsoft.liuw.dto;

import org.springframework.data.domain.Page;

/**
 * Created by liuw on 2017/4/21.
 */
public class PageData {

    public static PageData convert(final Page<?> page) {
        final PageData pageData = new PageData();
        pageData.setFirst(page.isFirst());
        pageData.setLast(page.isLast());
        pageData.setNumber(page.getNumber());
        pageData.setTotalPages(page.getTotalPages());
        pageData.setNumberOfElements(page.getNumberOfElements());
        pageData.setSize(page.getSize());
        pageData.setTotalElements(page.getTotalElements());
        return pageData;
    }

    /**
     * 总条数
     */
    private long totalElements;
    /**
     * 查询结果条数
     */
    private int numberOfElements;
    /**
     * 总页数
     */
    private int totalPages;
    /**
     * 是否第一页
     */
    private boolean first;
    /**
     * 是否最后页
     */
    private boolean last;
    /**
     * 页大小
     */
    private int size;
    /**
     * 当前页码（从0开始）
     */
    private int number;

    public long getTotalElements() {
        return this.totalElements;
    }

    public void setTotalElements(final long total) {
        this.totalElements = total;
    }

    public int getNumberOfElements() {
        return this.numberOfElements;
    }

    public void setNumberOfElements(final int rows) {
        this.numberOfElements = rows;
    }

    public int getTotalPages() {
        return this.totalPages;
    }

    public void setTotalPages(final int pages) {
        this.totalPages = pages;
    }

    public boolean isFirst() {
        return this.first;
    }

    public void setFirst(final boolean first) {
        this.first = first;
    }

    public boolean isLast() {
        return this.last;
    }

    public void setLast(final boolean last) {
        this.last = last;
    }

    public int getSize() {
        return this.size;
    }

    public void setSize(final int size) {
        this.size = size;
    }

    public int getNumber() {
        return this.number;
    }

    public void setNumber(final int page) {
        this.number = page;
    }

    public long getFromNumber() {
        if (this.numberOfElements == 0) {
            return this.number * this.size;
        } else {
            return this.number * this.size + 1;
        }
    }

    public long getToNumber() {
        return this.number * this.size + this.numberOfElements;
    }
}

