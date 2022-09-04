"use strict";

class MetersSerializer {

    /**
     * @param {{
     *     id: string,
     *     maxSpeed: number,
     *     time: number,
     *     newsId: string
     * }} meterData
     * */
    static serialize(meterData) {
        return new Meter(meterData);
    }

    /**
     * @param {Meter} meter
     * */
    static deserialize({id, maxSpeed, newsId, time}) {
        return {id, maxSpeed, newsId, time};
    }
}
