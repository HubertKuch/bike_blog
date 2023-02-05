"use strict";

class MetersSerializer {

    /**
     * @param {{
     *     id?: string,
     *     maxSpeed: number,
     *     time: number,
     *     newsId: string,
     *     endState: number,
     *     tripLength: number,
     *     startState: number
     * }} meterData
     * */
    static serialize(meterData) {
        return new Meter(meterData);
    }

    /**
     * @param {Meter} meter
     * */
    static deserialize({id, maxSpeed, newsId, time, starState, endState, tripLength}) {
        return {
            id,
            maxSpeed,
            newsId,
            time,
            endState: endState,
            starState: starState,
            tripLength
        };
    }
}
