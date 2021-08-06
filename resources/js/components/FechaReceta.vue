<template>
    <!--<span>{{formatearFecha}}</span>--> <!-- Es lo mismo mostrar la fecha -->
    <span v-text="formatearFecha"></span>
</template>

<script>
    export default {
        props:['fecha'],
        computed:{
            formatearFecha(){
                return moment(this.fecha).locale('es').format('DD [de] MMMM [del] YYYY');
            }
        }
        // mounted() {
        //     console.log('La fecha es',this.fecha);
        // },
    }
</script>