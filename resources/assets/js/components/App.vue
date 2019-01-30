<template>
    <v-app>
        <main fill-height style="height:100%;">
           <div style="height:100%;">
                <navbar></navbar>
                <container ></container>
                <foot></foot>
           </div>
        </main>
    </v-app>
</template>
<script>
import Navbar from './includes/Navbar.vue'
import Footer from './includes/Footer.vue'
import Body from './includes/Body.vue'

export default {
    components:{
        'navbar' : Navbar,
        'foot' : Footer,
        'container' : Body
    },
    mounted() {
        console.log('Component mounted.')
    }
}
</script>
