"use strict";

class Meter {

    constructor(data) {
        this.id = data.id;
        this.maxSpeed = data.maxSpeed;
        this.time = data.time;
        this.newsId = data.newsId;
        this.meterStartState = data.meterStartState;
        this.meterEndState = data.meterEndState;
        this.tripLength = data.tripLength;
    }
}
