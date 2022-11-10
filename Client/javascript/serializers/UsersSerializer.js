"use strict";

class UsersSerializer {

    /**
     * @param {User} data
     * @return User
     * */
    static serialize(data) {
        return new User(data);
    }

    /**
     * @param {User} user
     * @return User
     * */
    static deserialize(user) {
        return {...user};
    }
}
