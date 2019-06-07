<template>
    <div class="overlay" v-if="loading">
        <dot-loader :loading="loading"></dot-loader>
    </div>
</template>

<script>
export default {
    computed:{
        loading(){
            return this.$store.getters.getLoading;
        }
    }
}
</script>

<style lang="scss" scoped>
.overlay{
    background-color: #ccc9;
    width:100vw;
    height: 100vh;
    position:fixed;
    top:0;
    left:0;
    display:flex;
    justify-content: center;
    align-items:center;
    z-index:999;
}
</style>
