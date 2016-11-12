package com.jinson.irins.singlynews;

/**
 * Created by HP on 10-11-2016.
 */
public class Search {
    String heading;
    String content;
    String site;
    String url;

    public Search(String heading, String content, String site,String url) {
        this.heading = heading;
        this.content = content;
        this.site = site;
        this.url=url;
    }

    public String getHeading() {
        return heading;
    }

    public void setHeading(String heading) {
        this.heading = heading;
    }

    public String getSite() {
        return site;
    }

    public void setSite(String site) {
        this.site = site;
    }

    public String getContent() {
        return content;
    }

    public void setContent(String content) {
        this.content = content;
    }

    public String getUrl() {
        return url;
    }

    public void setUrl(String url) {
        this.url = url;
    }
}
