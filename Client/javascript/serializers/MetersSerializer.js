"use strict";

class MetersSerializer {

    /**
     * @param {{
     *     id?: string,
     *     maxSpeed: number,
     *     time: number,
     *     newsId: string,
     *     meterEndState: number,
     *     tripLength: number,
     *     meterStartState: number
     * }} meterData
     * */
    static serialize(meterData) {
        return new Meter(meterData);
    }

    /**
     * @param {Meter} meter
     * */
    static deserialize({id, maxSpeed, newsId, time, meterStartState, meterEndState, tripLength}) {
        return {
            id,
            maxSpeed,
            newsId,
            time,
            meterEndState,
            meterStartState,
            tripLength
        };
    }
}
