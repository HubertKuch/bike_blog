"use strict";

class NewsController {

    static baseUrl = "/bike-blog/api/v1/news/";

    /** @returns News[] */
    static async getNews() {
        const data = await this.fetchData(this.baseUrl);

        return data.map(news => NewsSerializer.serialize(news));
    }

    /**
     * @param {string} id
     * @return News
     * */
    static async getNewsById(id) {
        const data = await this.fetchData(`${this.baseUrl}/${id}`);

        return data.map(news => NewsSerializer.serialize(news))[0] ?? null;
    }

    static async fetchData(url) {
        const res = await fetch(url);

        return await res.json();
    }
}
