<template>
<div class="flex justify-center items-center bg-[var(--mainBlackColor)] w-full h-[100vh] ">
    <div class="flex-col items-center justify-center p-8 text-[var(--mainBlackColor)] bg-white rounded-md">
        <div class="flex justify-end pr-2">
            <p class=" font-semibold text-[22px]">Sign in</p>
        </div>
        <div class="flex-col w-72 text-[16px]">
            <div class="mb-2 form-group">
                <label for="name">Name</label><br>
                <input v-model="name" class="form-control-sm form-control text-[16px]" type="name" id="email" placeholder="name">
            </div>
            <div class="mb-2 form-group">
                <label for="email">Username</label><br>
                <input v-model="email" class="form-control-sm form-control text-[16px]" type="email" id="email" placeholder="email">
            </div>
            <div class="mb-2 form-group">
                <label for="password">Password</label>
                <input v-model="password" class="form-control form-control-sm text-[16px]" type="password" id="password" placeholder="password">
            </div>
            <div class="mb-2 form-group">
                <label for="confirm_password">Password confirm</label>
                <input v-model="password_confirmation" class="form-control form-control-sm text-[16px]" type="password" id="confirm_password" placeholder="confirm password">
            </div>
            <div class="flex gap-1">
                <input v-model="remember" type="checkbox" id="checkbox" >
                <label for="checkbox" class="cursor-pointer"> Remember me</label>
                
            </div>
            <div>
                <button @click.prevent="register" class="w-full h-8 leading-[16px] btn btn-primary rounded-sm">Sign in</button>
            </div>
        </div>
        <div class="flex justify-end gap-2">
                <p class="text-black">If you registered!</p>
                <span class="text-[var(--mainGreen)]">
                    <router-link :to="{name:'user.login'}">Log in</router-link>
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
            name:null,
            email:null,
            password:null,
            password_confirmation:null,
            remember:false,

        }
    },

    methods:{
        register(){
            axios.post('/api/auth/register',{name:this.name,email:this.email,password:this.password, password_confirmation:this.password_confirmation}).then((res) => {
                let token = res.data.access_token;
                localStorage.setItem('access_token',token);
                this.$router.push({name:'dashboard.index'})
                // const expires = new Date(Date.now() + 60 * 60 * 1000).toUTCString(); // 1 hour from now
                // document.cookie = `access_token=${token};expires=${expires};path=/;secure;SameSite=Strict;`; 
            }).catch(err =>{
                console.log(err.response)
            });
        }
    }
}
</script>

<style>
</style>