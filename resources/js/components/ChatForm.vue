<template>
    <div class="d-flex mx-3 my-3">
            <span class="mx-3 my-auto" style="cursor: pointer;" @click="openImageModal" >
                <svg fill="#000000" height="30px" width="30px" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 52 52" xml:space="preserve"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g> <path d="M14,24.138c3.071,0,5.569-2.498,5.569-5.568C19.569,15.498,17.071,13,14,13s-5.569,2.498-5.569,5.569 C8.431,21.64,10.929,24.138,14,24.138z"></path> <path d="M1,0v40v12h50V40V0H1z M3,2h46v27.643L37.676,19.262c-0.196-0.179-0.454-0.268-0.72-0.262 c-0.265,0.012-0.515,0.129-0.694,0.325l-8.363,9.159l3.808,3.808c0.391,0.391,0.391,1.023,0,1.414C31.512,33.902,31.256,34,31,34 s-0.512-0.098-0.707-0.293l-3.826-3.654l-4.743-4.743c-0.374-0.373-0.972-0.392-1.368-0.044L3.621,40H3V2z M49,50H3v-8h46V50z"></path> </g> </g></svg>                 
            </span>
            <input type="text" class="form-control me-3" v-model="message" v-on:keyup.enter="onEnter">
            <button class="button btn btn-primary" :disabled="buttonDisabled" @click="sendMessage">Send</button>
    </div>

    <div class="image-modal" v-if="isImageModalOpen">
        <div  ref="modalRef">
            <div class="vue-modal-content">
                <div class="d-flex justify-content-between align-items-center">
                    <h6>Please Select Image</h6>
                    <button type="button" @click="closeImageModal" class="px-3 bg-white" style="border:none; font-weight: bold; color:red;">x</button>
                </div>
                <div class="card">
                    <div class="drag-area" @dragover.prevent="onDragOver" @dragleave.prevent="onDragLeave" @drop.prevent="onDrop">
                        <span v-if="!isDragging">
                            Drag & drop image here or 
                            <span class="select" role="button" @click="selectFiles">
                                Choose
                            </span>
                        </span>
                        <div v-else class="select">
                            Drop images here
                        </div>
                        <input type="file" class="file" ref="fileInput" multiple @change="onFileSelect" />
                    </div>
                    <div class="container my-3 scrollable" >
                        <div class="image" v-for="(image, index) in images" :key="index">
                            <span class="delete" @click="deleteImage(index)">&times;</span>
                            <img :src="image.url" />
                        </div>
                    </div>
                </div>
                <div class="text-end">
                    <button type="button" class="btn btn-primary my-2" @click="sendImage">Send</button>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
    import '../../css/app.css';
    let formData = new FormData();
    export default {
        props: ['chat', 'token'],
        data() {
            return {
                isDragging: false,
                message: '',
                isImageModalOpen: false,
                images: [],
                upload_files: [],
                formData,
                buttonDisabled: false
            }
        },

        methods: {

             selectFiles() {
                this.$refs.fileInput.click();
            },

            onFileSelect(event) {  
                const files = event.target.files;
                this.upload_files = files;

                if(files.length === 0) return;
                for(let i = 0; i < files.length; i++) {
                    if(files[i].type.split("/")[0] != "image") continue;
                    this.formData.append('image', files[i]);
                    if(!this.images.some((e) => e.name === files[i].name)) {
                        this.images.push({name: files[i].name, url: URL.createObjectURL(files[i])});
                    }
                }
            },

            deleteImage(index) {
                this.images.splice(index, 1)
            },

            onDragOver(event) {
                event.preventDefault();
                this.isDragging = true;
                event.dataTransfer.dropEffect = "copy";
            },

            onDragLeave(event) {
                event.preventDefault();
                this.isDragging = false;
            },

            onDrop(event) {
                event.preventDefault();
                this.isDragging = false;
                const files = event.dataTransfer.files;
                this.upload_files = files;

                for(let i = 0; i < files.length; i++) {
                    if(files[i].type.split("/")[0] != "image") continue;
                    if(!this.images.some((e) => e.name === files[i].name)) {
                        this.images.push({name: files[i].name, url: URL.createObjectURL(files[i])});
                    }
                }
            },

            // image modal
            openImageModal() {
                this.isImageModalOpen = true;
                document.addEventListener('mouseup', this.closeImageModalOnClickOutside);
            },

            closeImageModal() {
                this.isImageModalOpen = false;
                document.removeEventListener('mouseup', this.closeImageModalOnClickOutside);
            },

            closeImageModalOnClickOutside(event) {
                const modal = this.$refs.modalRef;
                if (!modal.contains(event.target)) {
                    this.closeImageModal();
                }
            },

            sendImage() {
                for(let i = 0; i < this.upload_files.length; i++) {
                    let newFormData = new FormData();
                    newFormData.append('message', this.upload_files[i]);
                    newFormData.append('chat_id', this.chat.id);
                    newFormData.append('type', 1);

                    setTimeout(() => {
                        axios.request({
                        headers: {
                            Authorization: `Bearer ${this.token}`
                        },
                        method: "POST",
                        url: `/api/chat-message`,
                        data: newFormData,
                        }).then(response => { 
                            this.images = [];
                        });
                    }, i * 2000); 
                }

                this.closeImageModal();
            },

            scrollBottom() {
                var scrollableDiv = document.getElementById("scrollableDiv");
                scrollableDiv.scrollTop = scrollableDiv.scrollHeight;
            },

            onEnter() {
                this.sendMessage();  
            },

            // send message when button click (send) and button disabled till 2 seconds
            sendMessage() {

                if(this.message.length !== 0) {
                    this.buttonDisabled = true;
                    axios.request({
                        headers: {
                            Authorization: `Bearer ${this.token}`
                        },
                        method: "POST",
                        url: `/api/chat-message`,
                        data: {
                            message: this.message,
                            chat_id: this.chat.id
                        },
                        }).then(response => { 
                            this.message = '';
                            setTimeout(() => {
                                this.buttonDisabled = false;
                                this.scrollBottom();
                            }, 2000);    
                    });
                }
            },
        }
    }
</script>