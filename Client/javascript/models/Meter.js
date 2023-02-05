"use strict";

class Meter {

    constructor(data) {
        this.id = data.id;
        this.maxSpeed = data.maxSpeed;
        this.time = data.time;
        this.newsId = data.newsId;
        this.startState = data.startState;
        this.endState = data.endState;
        this.tripLength = data.tripLength;
    }
}
