"use strict";

class NewsController {

    static baseUrl = "/bike-blog/api/v1/news/";

    /**
     * @param {News} news
     * @return undefined
     * */
    static async saveNews(news) {
        const newsData = NewsSerializer.deserialize(news);

        await fetch(this.baseUrl, {
            method: "POST",
            body: JSON.stringify(newsData)
        });
    }

    /** @returns Promise<News[]> */
    static async getNews() {
        const data = await this.fetchData(this.baseUrl);

        return data.map(news => NewsSerializer.serialize(news));
    }

    /**
     * @param {string} id
     * @return Promise<News>
     * */
    static async getNewsById(id) {
        const data = await this.fetchData(`${this.baseUrl}/${id}`);

        return data.map(news => NewsSerializer.serialize(news))[0] ?? null;
    }

    /**
     * @param {string} tag
     * @return Promise<News[]>
     * */
    static async getNewsByTag(tag) {
        const data = await this.fetchData(`${this.baseUrl}/tag/${tag}`);

        return data.map(news => NewsSerializer.serialize(news));
    }

    /**
     * @param {string} url
     * @return Promise<any>
     * */
    static async fetchData(url) {
        const res = await fetch(url);

        return await res.json();
    }
}
