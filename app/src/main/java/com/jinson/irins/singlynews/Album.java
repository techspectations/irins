package com.jinson.irins.singlynews;

public class Album {

    String heading;
    String content;
    String cat;

    public Album(String head, String cont,String cate) {
        this.heading=head;
        this.content=cont;
        this.cat=cate;

    }

    public String getHeading() {
        return heading;
    }

    public void setHeading(String heading) {
        this.heading = heading;
    }

    public String getContent() {
        return content;
    }

    public void setContent(String content) {
        this.content = content;
    }

    public String getCat() {
        return cat;
    }

    public void setCat(String cat) {
        this.cat = cat;
    }
}