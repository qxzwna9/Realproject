<template>
  <div class="member-welcome-bg">
    <v-container class="fill-height">
      <v-row justify="center" align="center">
        <v-col cols="12" md="8" lg="6">
          <v-card flat color="transparent" class="text-center pa-md-10">
            <v-avatar color="rgba(212, 175, 55, 0.1)" size="120" class="mb-6">
              <v-icon size="60" color="primary">mdi-account-circle-outline</v-icon>
            </v-avatar>

            <h1 class="welcome-title white--text mb-3">
              Welcome, {{ userName }}!
            </h1>

            <p class="welcome-subtitle grey--text text--lighten-1 mx-auto" style="max-width: 450px;">
              Discover our exclusive collection and enjoy a seamless shopping experience tailored just for you.
            </p>

            <div class="mt-10">
              <v-btn
                x-large
                color="primary"
                dark
                class="font-weight-bold mx-2"
                to="/shirt"
              >
                <v-icon left>mdi-shopping-outline</v-icon>
                Shop Collection
              </v-btn>
              <v-btn
                x-large
                outlined
                color="primary"
                class="mx-2"
                to="/profile"
              >
                <v-icon left>mdi-account-edit-outline</v-icon>
                My Profile
              </v-btn>
            </div>
          </v-card>
        </v-col>
      </v-row>
    </v-container>
  </div>
</template>

<script>
import { mapState } from 'vuex';

export default {
  middleware: 'auth',
  head() {
    return {
      title: 'Welcome'
    }
  },
  computed: {
    ...mapState(['user']),
    userName() {
      return this.user ? this.user.name : 'Member';
    }
  }
}
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Jost:wght@300;400&display=swap');

.member-welcome-bg {
  min-height: 100vh;
  width: 100%;
  background: #0A0A0A;
  display: flex;
  align-items: center;
  justify-content: center;
}

.welcome-title {
  font-family: 'Playfair Display', serif;
  font-size: 3.5rem;
  font-weight: 700;
  letter-spacing: 1px;
  text-shadow: 0 2px 10px rgba(0,0,0,0.3);
  color: #D4AF37 !important; /* Gold color */
}

.welcome-subtitle {
  font-family: 'Jost', sans-serif;
  font-size: 1.1rem;
  line-height: 1.8;
}

.v-btn {
  border-radius: 8px;
  text-transform: none;
  letter-spacing: 0.5px;
  font-size: 1rem;
}

.v-avatar {
  border: 2px solid rgba(212, 175, 55, 0.2);
}
</style>