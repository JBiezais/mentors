<template>
    <div class="flex flex-wrap">
        <div class="flex bg-gray-400 rounded-lg text-gray-100" >
          <div class="flex border-r border-gray-100 px-5 py-3 cursor-pointer rounded-l-lg" :class="filterForm.type === 'all'? 'bg-gray-700': 'bg-gray-400'" @click="this.filterForm.type = 'all'; getFilteredProps()">
            <h1 class="my-auto">Visi</h1>
          </div>
          <div class="flex border-r border-gray-100 px-5 py-3 cursor-pointer" :class="filterForm.type === 'confirmed'? 'bg-gray-700': 'bg-gray-400'" @click="this.filterForm.type = 'confirmed'; getFilteredProps()">
            <h1 class="my-auto"><slot name="first"></slot></h1>
          </div>
          <div class="flex px-5 py-3 cursor-pointer rounded-r-lg" :class="filterForm.type === 'requested'? 'bg-gray-700': 'bg-gray-400'" @click="this.filterForm.type = 'requested'; getFilteredProps()">
            <h1 class="my-auto"><slot name="second"></slot></h1>
          </div>
        </div>
        <div class="flex bg-gray-700 px-5 py-3 rounded-lg text-gray-100 cursor-pointer extraMail">
          <h1 class="my-auto">Sūtīt ziņu</h1>
        </div>
        <div class="w-96 extraSearch">
          <label for="default-search" class="mb-2 text-sm font-medium text-gray-900 sr-only">Search</label>
          <div class="relative">
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
              <svg aria-hidden="true" class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                   xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
              </svg>
            </div>
            <input type="search" id="default-search" v-model="filterForm.keyword"
                   class="block w-full p-4 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-200 focus:ring-blue-500 focus:border-blue-500"
                   placeholder="Search Name, Last Name">
            <button @click="getFilteredProps()"
                    class="text-white absolute right-2.5 bottom-2.5 bg-gray-700 hover:bg-gray-900 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2">
              Search
            </button>
          </div>
        </div>
    </div>
    <div class="flex space-x-3">
        <h1 class="text-gray-800 my-auto">Fakultātes: </h1>
        <div class="flex flex-wrap">
            <div class="py-3 px-5 rounded-xl overflow-hidden w-fit ml-3 cursor-pointer" :class="filterForm.faculty === null? 'bg-gray-400': 'bg-gray-200 '" @click="filterForm.faculty = null; filterForm.program = null; getFilteredProps()">
                <h1>Visas</h1>
            </div>
            <div class="py-3 px-5 rounded-xl overflow-hidden w-fit ml-3 cursor-pointer bg-gray-200 border border-gray-300" :class="filterForm.faculty === faculty.id.toString()? 'bg-gray-400': 'bg-gray-200 '" @click="this.filterForm.faculty = faculty.id; filterForm.program = null; getFilteredProps()" v-for="faculty in faculties">
                <h1>{{faculty.code}}</h1>
            </div>
        </div>
    </div>
    <div class="flex space-x-3" v-if="filterForm.faculty">
        <h1 class="text-gray-800 my-auto">Studiju programmas: </h1>
        <div class="flex flex-wrap">
            <div class="py-3 px-5 rounded-xl overflow-hidden w-fit ml-3 cursor-pointer" :class="filterForm.program === null? 'bg-gray-400': 'bg-gray-200 '" @click="filterForm.program = null; getFilteredProps()">
                <h1>Visas</h1>
            </div>
            <div class="py-3 px-5 rounded-xl overflow-hidden w-fit ml-3 cursor-pointer bg-gray-200 border border-gray-300" :class="filterForm.program === program.id.toString()? 'bg-gray-400': 'bg-gray-200 '" @click="this.filterForm.program = program.id; getFilteredProps()" v-for="program in programs.programs">
                <h1>{{program.code}}</h1>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    name: 'FilterBar',
    props:{
        faculties: Object,
        keyword: String,
        type: String,
        program: String,
        faculty: String,
    },
    data(){
        return{
            programs:{},
            filterForm:{
                keyword: this.keyword || '',
                type: this.type || 'all',
                program: this.program || null,
                faculty: this.faculty || null,
            }
        }
    },
    methods:{
        getFilteredProps(){
            this.$emit('filter', this.filterForm)
        }
    },
    mounted(){
        this.programs = this.faculties.find(faculty => faculty.id.toString() === this.filterForm.faculty)
    }
}
</script>
<style>
.extraSearch {
  margin-top: 0;
  margin-left: 1.25rem;
}

.extraMail {
  margin-top: 0;
  margin-left: 1.25rem;
}

@media (max-width: 486px) {
  .extraMail {
    margin-top: 1.25rem;
    margin-left: 0;
  }
}

@media (max-width: 890px) {
  .extraSearch {
    margin-top: 1.25rem;
    margin-left: 0;
  }
}
</style>
