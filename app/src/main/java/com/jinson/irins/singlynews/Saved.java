package com.jinson.irins.singlynews;

/**
 * Created by HP on 20-10-2016.
 */
public class Saved  {

    String title,content;

    public Saved() {
    }

    public Saved(String title, String content) {
        this.title = title;
        this.content = content;
    }

    public String getTitle() {
        return title;
    }

    public void setTitle(String title) {
        this.title = title;
    }

    public String getContent() {
        return content;
    }

    public void setContent(String content) {
        this.content = content;
    }
}

