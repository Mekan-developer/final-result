<template>
    <div class="food-create">
        <div class="px-6 py-4 text-blue-500 bg-gray-900 rounded-lg shadow-lg">
            <div class="p-1 m-2">
                <div class="flex items-center justify-between mb-4">
                    <div class="text-xl font-semibold text-center text-white">Food Add</div>
                    <i @click="$emit('popupDeleteCreate')" 
                        class="p-2 text-2xl text-white cursor-pointer bx bx-x hover:text-red-500">
                    </i>
                </div>
                <div class="flex justify-start gap-2 mb-4">
                    <div class="w-full">
                        <label class="mb-0 form-label">Choose category</label>
                        <select class="category-select-style form-select" aria-label="Default select example" @change="childValNull" v-model="selectedParentCategory">
                            <option hidden :value="null">Choose category</option>
                            <option v-for="parent in parentCategories" :key="parent.id" :value="parent.id">
                                {{ parent.name[language] }}
                            </option>
                        </select>
                        <span class="text-red-600"  v-if="!selectedParentCategory && error">{{ errors.selectedParentCategory }}</span>
                    </div>
                    <div class="w-full" v-if="!(filteredChildCategories.length === 0)">
                        <label class="mb-0 form-label">choose sub category</label>
                        <select class="category-select-style form-select" aria-label="Default select example" v-model="selectedChildCategory">
                            <option :value="null">Select sub category</option>
                            <option v-for="child in filteredChildCategories" :key="child.id" :value="child.id">
                                {{ child.name[language] }}
                            </option>
                        </select>
                        <span class="text-red-600" v-if="!selectedChildCategory && error">{{ errors.selectedChildCategory }}</span>
                    </div>
                </div>
                <div class="flex flex-wrap justify-center gap-4 mb-4">
                    <div class="flex-grow mb-2 overflow-hidden" v-for="(lang, code) in languages" :key="code">
                        <label :for="'input-' + code" class="mb-0 form-label">Food name {{ code }}</label>
                        <input type="text" v-model.trim="name[code]" class="custom-input form-control" :id="'input-' + code" placeholder = 'Foods name' />
                        <span class="text-red-600" v-if="!name[code] && error">{{ errors.name[code] }}</span>
                    </div>
                </div>
                <div class="mb-4">
                    <label for="formFileMultiple" class="mb-0 form-labe">Menu Image</label>
                    <input class="custom-input form-control" type="file" id="formFileMultiple" @change="onChange" multiple />
                    <span class="text-red-600"  v-if="!errImage && error">{{ errors.image }}</span>
                </div>
                <div class="flex gap-4 mb-4 text-blue-500">
                    <div v-if="this.isEmpty(sizeData)" class="w-full">
                        <label for="price" class="mb-0 form-label">Price</label>
                        <input class="custom-input form-control" v-model="price" type="number" id="price" placeholder="Price" />
                        <span class="text-red-600" v-if="!price && error">{{ errors.price }}</span>
                    </div>
                    <div class="flex w-full gap-4">
                        <div class="flex-grow">
                            <label for="discount" class="mb-0 form-label">Discount</label>
                            <input class="custom-input form-control" v-model="discount" type="number" id="discount" placeholder="Discount" />
                        </div>

                        <div>
                            <div class="flex items-center gap-2 -mt-[2px]">
                                <label class="mb-0 switch">
                                    <input type="checkbox" v-model="addFoodSizeActive" />
                                    <span class="slider round"></span>
                                </label>
                                <i v-if="this.isEmpty(sizeData)" class="bx bxs-x-circle text-red-500 text-[26px]"></i>
                                <i v-if="!this.isEmpty(sizeData)" class="bx bx-check-square text-green-500 text-[26px]"></i>
                                <div class="flex-nowrap">+Size</div>
                            </div>
                            <select class="select-style form-select" v-model="discount_unit">
                                <option selected value="manat">TMT</option>
                                <option value="percent">%</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2">
                        <label class="switch">
                            <input type="checkbox" v-model="isActive" id="active">
                            <span class="slider round"></span>
                        </label>

                        <span class="">Is Active</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <label class="switch">
                            <input type="checkbox" v-model="descriptionActive" id="checkbox">
                            <span class="slider round"></span>
                        </label>
                        <i v-if="this.isEmpty(descriptionData)" class="bx bxs-x-circle text-red-500 text-[26px]"></i>
                        <i v-if="!this.isEmpty(descriptionData)" class="bx bx-check-square text-green-500 text-[26px]"></i>
                        <span class="">Description</span>
                    </div>
                </div>
                
                <description-component v-if="descriptionActive" :languages="languages" @updateDescActVal="updateDescActVal" @descriptionDataFun="descriptionDataFun" :descriptionData="descriptionData"></description-component>
                <size-component v-if="addFoodSizeActive" :languages="languages" @cancelSize="cancelFoodSize" @removeSizeData="removeSizeData" @sizeDataFun="sizeDataFun" :sizeData="sizeData"></size-component>
                <div class="mt-4">
                    <button class="w-full px-4 py-2 font-bold text-white bg-blue-500 rounded hover:bg-blue-600" type="button" @click.prevent="storeFoods">Save</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import api from "../../../../../api";
import DescriptionComponent from "./components/DescriptionComponent.vue";
import SizeComponent from "./components/SizeComponent.vue";

export default {
    props: {
        language: String,
        languages: Object,
    },
    emit: ["popupDeleteCreate","getFoods"],
    data() {
        const name = {};
        return {
            name: name,
            image: [],
            price: null,
            discount: null,
            discount_unit: "manat",
            isActive: true,
            selectedParentCategory: null,
            selectedChildCategory: null,
            descriptionData: {},
            sizeData: [],

            parentCategories: [],
            childCategories: [],
            onePriceActive: true,
            descriptionActive: false,
            addFoodSizeActive: false,
            errors: {
                name: {},
            },
            error: false,
            errorImage: false,
            isLoading: false,
        };
    },
    created() {
        this.getCategories();
        this. input_names();
    },
    methods: {
        validations() {
            if (!this.selectedParentCategory) this.errors.selectedParentCategory = "Parent category is required."; // parent category validation
            else delete this.errors.selectedParentCategory;
            //child category validation
            if (this.filteredChildCategories.length != 0) {
                if (!this.selectedChildCategory) this.errors.selectedChildCategory = "Child category is required.";
                else delete this.errors.selectedChildCategory;
            }
            //name validation
            Object.keys(this.languages).forEach((code) => {
                if (!this.name[code]) {
                    this.errors.name[code] = `Name ${code} is required.`;
                    this.error = true
                } else if (this.name[code].length < 3) {

                 this.errors.name[code] = `Name ${code} must be at least 3 characters.`;
                } else delete this.errors.name[code];
            });
            if (this.image.length === 0) this.errors.image = "Image is required."; //Image validation
            else delete this.errors.image;
            //price validation
            if (this.sizeData.length == 0) {
                if (!this.price) this.errors.price = "Food's price is required.";
                else delete this.errors.price;
            }
        },
        storeFoods() {
            this.validations();
            if (this.isLoading) 
                return;
            this.isLoading = true;
            if (Object.keys(this.errors).length === 1 && Object.keys(this.errors.name).length === 0) {
                let formData = new FormData();
                if (this.image.length > 0) Object.keys(this.image).forEach((key) => formData.append("image[]", this.image[key]));
                formData.append("name", JSON.stringify(this.name));
                formData.append("description", JSON.stringify(this.descriptionData));
                formData.append("category_id", this.selectedChildCategory ? this.selectedChildCategory : this.selectedParentCategory);
                formData.append("discount", this.discount);
                formData.append("discount_unit", this.discount_unit);
                formData.append("is_active", JSON.stringify(this.isActive));
                if (this.sizeData.length > 0) formData.append("food_sizes", JSON.stringify(this.sizeData));
                if (this.sizeData.length == 0) formData.append("price", this.price);
                api.post("/api/foods", formData).then((res) => {
                    this.$emit('popupDeleteCreate');
                    this.$emit('getFoods');
                });
            }
        },
        input_names() {
            Object.keys(this.languages).forEach((code) => {
                this.name[code] = "";
            });
        },
        getCategories() {
            api.get("/api/food-create").then((res) => {
                this.childCategories = res.data.child;
                this.parentCategories = res.data.parent;
            });
        },
        childValNull() {
            this.selectedChildCategory = null;
        },
        onChange(e) {
            this.image = e.target.files;            
        },
        cancelFoodSize() {
            this.addFoodSizeActive = false;
        },
        updateDescActVal() {
            this.descriptionActive = false;
        },
        descriptionDataFun(data) {
            this.descriptionData = {};
            if (!this.isEmpty(data)) {
                this.descriptionData = data;
            }
            this.descriptionActive = false;
        },
        sizeDataFun(data) {
            data.forEach((item, index) => {
                this.sizeData[index] = item;
            });
            this.cancelFoodSize();
        },
        removeSizeData() {
            this.sizeData = [];
            this.cancelFoodSize();
        },
        isEmpty(obj) {
            return Object.keys(obj).length === 0;
        },
    },
    computed: {
        filteredChildCategories() {
            return this.childCategories.filter((child) => child.parent_id === this.selectedParentCategory);
        },
        errImage(){
            if (this.image.length > 0)
                return true
            return false
        }
        
    },
    components: {
        DescriptionComponent,
        SizeComponent,
    },
};
</script>

<style>
.food-create {
    @apply absolute top-0 left-0 z-50 flex flex-col items-center justify-center w-full h-full px-4 bg-gray-800 bg-opacity-75 text-[#0d6efd];
}

.custom-input {
    @apply p-2 text-gray-800 bg-gray-200 border border-gray-400 rounded focus:outline-none focus:border-blue-500;
}
.select-style{
    @apply p-2 text-gray-800 bg-gray-200 border border-gray-400 rounded focus:outline-none focus:border-blue-500;
}

.category-select-style{
    @apply p-2 text-gray-800 bg-gray-200 border border-gray-400 rounded cursor-pointer focus:outline-none focus:border-blue-500;
}

</style>
