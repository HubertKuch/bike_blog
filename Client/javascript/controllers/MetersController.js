"use strict";

class MetersController {

    static baseUrl = "/bike-blog/api/v1/meters/"

    /**
     *  @param {string} id
     * */
    static async getMetersForNews(id) {
        const data = await this.fetchData(`${this.baseUrl}/${id}`);

        return data.map(meter => MetersSerializer.serialize(meter));
    }

    /**
     * @param {Meter} meter
     * */
    static async addMeterToNews(meter) {
        const meterData = MetersSerializer.deserialize(meter);

        await fetch(this.baseUrl, {method: "POST"});
    }

    static async fetchData(url) {
        const res = await fetch(url);
        const data = await res.json();

        return data;
    }
}
