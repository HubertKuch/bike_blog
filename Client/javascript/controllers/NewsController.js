"use strict";

class NewsController {

    static baseUrl = "/bike-blog/api/v1/news/";

    /** @returns News[] */
    static async getNews() {
        const res = await fetch(this.baseUrl);
        const data = await res.json();

        return data.map(news => NewsSerializer.serialize(news));
    }
}
