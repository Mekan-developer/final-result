<template>
<div class="flex justify-center items-center bg-[var(--mainBlackColor)] w-full h-[100vh] ">
    <div class="flex-col items-center justify-center p-8 text-[var(--mainBlackColor)] bg-white rounded-md">
        <div class="flex justify-end pr-2">
            <p class=" font-semibold text-[22px]">Sign in</p>
        </div>
        <div class="flex-col w-72 text-[18px]">
            <div class="mb-2 form-group">
                <label for="email">Username</label><br>
                <input v-model="email" class="form-control-sm form-control text-[16px]" type="email" id="email" placeholder="email">
            </div>
            <div class="mb-2 form-group">
                <label for="password">Password</label>
                <input v-model="password" class="form-control form-control-sm text-[16px]" type="password" name="" id="password" placeholder="password">
            </div>
            <div v-if="error" class="text-red-600 text-[16px]">{{ error }}</div>
            <div class="flex justify-between text-[16px]">
                <div class="flex gap-1">
                    <input v-model="remember" type="checkbox" id="checkbox" >
                    <label for="checkbox" class="cursor-pointer"> Remember me</label>
                </div> 
                <div class="flex">
                    <p>
                        <span class="text-black">Forgot </span>
                        <a class="text-[var(--mainGreen)]" href="#">password?</a>
                    </p>
                </div>
            </div>
            <div class="">
                <button @click.prevent="login" class="w-full h-8 leading-[16px] btn btn-primary rounded-sm">Sign in</button>
            </div>
        </div>
        <div class="flex gap-2">
                <p >if you not registered yet!</p>
                <span class="text-[#2da5dd]">
                    <router-link :to="{name:'user.register'}">register</router-link>
                </span>
        </div>
    </div>
</div>
</template>

<script>
import axios from 'axios';



export default{
    data(){
        return{
            email:null,
            password:null,
            remember:false,
            error:null
        }
    },

    methods:{
        login(){
            axios.post('/api/auth/login',{email:this.email,password:this.password}).then((res) => {
                let token = res.data.access_token;
                localStorage.setItem('access_token',token);
                this.$router.push({name:'dashboard.index'})
                // const expires = new Date(Date.now() + 60 * 60 * 1000).toUTCString(); // 1 hour from now
                // document.cookie = `access_token=${token};expires=${expires};path=/;secure;SameSite=Strict;`; 
            }).catch(error => {
                this.error = error.response.data.error
            });
        }
    }
}
</script>

<style>
</style>