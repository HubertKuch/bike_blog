"use strict";

class User {

    /**
     * @param {{
     *     id: string,
     *     username: string,
     *     email: string,
     *     passwordHash?: string,
     *     ip: string,
     *     role: string
     * }} data
     * */
    constructor(data) {
        this.id = data.id;
        this.username = data.username;
        this.email = data.email;
        this.passwordHash = data.passwordHash;
        this.ip = data.ip;
        this.role = data.role;

    }
}
