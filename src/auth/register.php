<form action="/auth?login" method="POST">
    <section>
        <label for="email">Email</label>
        <input type="text" id="email" name="email">
    </section>
    <section>
        <label for="password">Password</label>
        <input type="text" id="password" name="password">
    </section>
    <section>
        <label for="gender">Gender</label>
        <select name="gender" id="gender">
            <option value="male">Male</option>
            <option value="female">Female</option>
            <option value="non-binary">Non Binary</option>
        </select>
    </section>
    <section>
        <label for="liked-gender">Liked Gender</label>
        <select name="liked-gender" id="liked-gender">
            <option value="male">Male</option>
            <option value="female">Female</option>
            <option value="everyone">Everyone</option>
        </select>
    </section>
    <section>
        <label for="age">Age</label>
        <input type="number" id="age" name="age">
    </section>
    <section>
        <label for="bio">Bio</label>
        <textarea id="bio" name="bio">
    </section>
    <!-- TODO: image  -->
    <button type="submit">Login</button>
</form>