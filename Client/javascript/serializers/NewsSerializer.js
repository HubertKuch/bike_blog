"use strict";

class NewsSerializer {

    /**
     * @return NewsGroup
     **/
    static serializeGroup(data) {
        const news = data.news.map(news => this.serializeSingleNews(news));
        return new NewsGroup({year: data.year, news});
    }

    /** @return News */
    static serializeSingleNews(data) {
        return new News(data);
    }

    /**
     * @param {News} news
     * @return {{date, description, title, tags}}
     * */
    static deserializeSingleNews({title, date, tags, description}) {
        return {title, date, tags, description};
    }
}
