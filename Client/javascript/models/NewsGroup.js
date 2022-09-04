"use strict";

class NewsGroup {

    /**
     * @param {{ news: News[], year: number }} props
     * */
    constructor(props) {
        this.year = props.year;
        this.news = props.news;
    }
}
