<template>
    <tr>
        <td>
            <img v-if="product.images && product.images.length > 0" 
                :src="product.images[0].url" 
                style="width :150px" 
                :alt="product.name" 
                @click="imgModal()">
            <img v-else src="/storage/images/app/no-image.png" :alt="product.name"  @click="imgModal(product)">
        </td>
        <td>
            <input v-model.lazy="product.code" @change="saveChange(product,'code')" 
                    type="text" class="form-control smallField">
        </td>
        <td >
            <select class="form-control" v-model="product.suplier_id" 
                    @change="changed(product,'suplier')">
                <option v-for="suplier in supliers" 
                        :key="suplier.id" 
                        :value="suplier.id"
                        :selected="suplier.id == product.suplier_id"> 
                    {{suplier.name}} 
                </option>
            </select>
        </td>
        <td>
            <select class="form-control" v-model="product.category_id"
                    @change="changed(product,'category')" >
                <option v-for="category in categories" 
                        :key="category.id" 
                        :value="category.id"
                        :selected ="product.category.id == category.id"
                        > 
                    {{category.name}} 
                </option>
            </select>
        </td>
        <td >
            <textarea placeholder="NOMBRE" rows="2" type="text" v-model.lazy="product.name" 
                    @change="saveChange(product,'name')" class="form-control"></textarea>
        
            <textarea placeholder="DESCRIPCION" rows="2" type="text" v-model.lazy="product.description" 
                    @change="saveChange(product,'description')" class="form-control"></textarea>
        
        </td>
        <td>
            <div class="row w-100 d-flex align-items-center">
                $<input v-model.lazy="product.price" @change="saveChange(product,'price')"
                        type="number" step=".01" class=" form-control smallField">
                        
            </div>
        
        </td>
        <td    class="smallField">
            <input step="1" v-model.lazy="product.pck_units" @change="saveChange(product,'pck_units')"
                type="number" class="form-control smallField ">
        </td>
        <td>
            <div class="row w-100 d-flex align-items-center">
                
                $<input  v-model.lazy="product.pck_price" 
                        @change="saveChange(product,'pck_price')"
                        
                type="number" step=".01" class="form-control smallField">
            </div>
        
        </td>
        <td class="d-flex flex-column justify-content-center align-items-center p-0">
           <!--  <input class="form-control" type="checkbox" v-model="product.selected"> -->
            <button @click.prevent="deleteProduct(product)" class="btn btn-sm btn-outline-danger m-1">
                <fa-icon icon="trash"></fa-icon>
            </button>
            <button @click.prevent="togglePause(product)" class="btn btn-sm m-1" :class="{'btn-info' : !product.paused, 'btn-success': product.paused}">
                <fa-icon v-if="product.paused" icon="play" class="text-white"></fa-icon>
                <fa-icon v-else icon="pause-circle" class="text-white"></fa-icon>
            </button>
            <button @click.prevent="toggleOffer(product)" class="btn btn-sm m-1" :class="{'btn-secondary' : !product.offer, 'btn-info': product.offer}">
                Oferta
            </button>
            
        </td>
        <image-modal v-if="product && showModal" :product="product"  
                            ref="modal" @refresh="refresh()"
                        @closedModal="showModal=false">
        </image-modal>
    </tr>
</template>

<script>
import imageModal from './Img-modal.vue';
export default {
    components:{imageModal},
    props:['product','supliers','categories'],
    data(){return{
        showModal:false
    }},
    methods:{
            refresh(){
                this.$emit('refresh');
            },
            saveChange(product,field){
                var data = {
                    product : product.id,
                    field : field,
                    value : product[field]
                }
               this.$http.put('/admin/product/update',data);
              
            },
            togglePause(product){
                var vm = this;
                product.paused = !product.paused;
                vm.saveChange(product,'paused');
                
            },
            toggleOffer(product){
                var vm = this;
                product.offer = !product.offer;
                vm.saveChange(product,'offer');
              

                
            },
            deleteProduct(product){
                var vm = this;
                swal({
                    title: "¿Estas seguro de elimiar este producto?",
                    text: "",
                    type: "warning",
                    buttons: [
                        'Cancelar',
                        'Borrar!'
                    ],
                    dangerMode: true,
                    confirmButtonColor: '#DD6B55',
                    confirmButtonText: 'Borrar!',
                    cancelButtonText: "Cancelar!",
                    closeOnConfirm: false,
                    closeOnCancel: false
                }).then((isConfirm) => {
                    if (isConfirm){
                         this.$http.delete('/admin/product/'+product.id)
                            .then(response => {
                         vm.$emit('refresh');   
                    });

                    } 
                })      
                
               
            },
            
            changed(product,field){ 
               this.saveChange(product,field+'_id');
                
            },
            imgModal(){

            
                this.showModal = true;

               setTimeout(() => {
                   let element = this.$refs.modal.$el;
                   $(element).modal('show');
               }, 100);
            },
    }
}
</script>

<style scoped lang="scss">
    


input[type="checkbox"]{
    width: 25px;
    margin:  10px;
    height: 20px;
}


.smallField{width: 80px;}
td {min-width: 120px;}
.btn-link {color : black;}
    td img {
        width: 10vw;
    }
   @media(max-width: 600px){
       table,.container,.card,.card-body {font-size: 0.66rem ; padding : 0}
       
       th,td, input{
           white-space:nowrap;
           width: 13vw;
           padding: 2px;
       }
       
       
        
       .nametd {width: 25vw;}
   }
</style>
