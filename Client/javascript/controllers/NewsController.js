"use strict";

class NewsController {

    static baseUrl = "/bike-blog/api";

    /**
     * @param {News} news
     * @return undefined
     * */
    static async saveNews(news) {
        const newsData = NewsSerializer.deserializeSingleNews(news);

        await fetch(`${this.baseUrl}/v1/news/`, {
            method: "POST",
            body: JSON.stringify(newsData)
        });
    }

    /** @returns Promise<NewsGroup[]> */
    static async getNews() {
        const data = await this.fetchData(`${this.baseUrl}/v2/news/`);

        return data.map(newsGroup => NewsSerializer.serializeGroup(newsGroup));
    }

    /**
     * @param {string} id
     * @return Promise<News>
     * */
    static async getNewsById(id) {
        const data = await this.fetchData(`${this.baseUrl}/v1/news/${id}`);

        return NewsSerializer.serializeSingleNews(data) ?? null;
    }

    /**
     * @param {string} tag
     * @return Promise<News[]>
     * */
    static async getNewsByTag(tag) {
        const data = await this.fetchData(`${this.baseUrl}/v3/news/tag/${tag}`);

        return data.map(news => NewsSerializer.serializeGroup(news));
    }

    /**
     * @returns Promise<Tag[]>
     * */
    static async getTags() {
        const res = await fetch(`${this.baseUrl}/v1/tags`, {
            method: "GET",
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json"
            }
        });

        return await res.json();
    }

    /**
     * @param {string} url
     * @return Promise<any>
     * */
    static async fetchData(url) {
        const res = await fetch(url);

        return await res.json();
    }

    static async getNewsTags(newsId) {
        return await this.fetchData(`${this.baseUrl}/v1/tags/${newsId}`);
    }

    /**
     * @returns Promise<Array<Array<Number>>>
     * */
    static async getGpsData(newsId) {
        return await this.fetchData(`${this.baseUrl}/v1/gps/${newsId}`);
    }

    /**
     * @returns Promise<Array<{id: string, newsId: string, name: string }>>
     * */
    static async getNewsImages($newsId) {
        return await this.fetchData(`${this.baseUrl}/v1/images/news/${$newsId}`);
    }
}
