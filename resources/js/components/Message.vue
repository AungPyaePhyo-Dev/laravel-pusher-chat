<template>	
    <div v-if="currentFilteredUser">
        <div class="chat">
            <div class="card-body" ref="hasScrolledToBottom" v-for="(chatGroup, date) in groupedChats" :key="date">
                <div class="text-center">
                    {{ getDay(date) }}
                </div>
                <div v-for="chat in chatGroup" :key="chat.id" >
                    <div class="message message-receive" v-if="chat.user.id != loggedInUser.id">
                        <div  v-if="chat.type == 1">
                                <div class="d-flex align-items-center">
                                    <img style="width:200px;" class="rounded my-2" :src="getChatImageUrl(chat.message)"  alt="">
                                    <span class="mx-2">{{ getHourAndMinutes(chat.created_at) }}</span>
                                </div>
                        </div>
                        <div v-else>
                            <p>
                                <strong class="primary-font">
                                You ( {{ chat.user.username }} ):
                                </strong>
                                <span>
                                    {{ chat.message }}
                                </span>
                                <br>
                                <span>{{ getHourAndMinutes(chat.created_at) }}</span>
                            </p> 
                        </div>
                    </div>
                    <div class="message message-send" v-if="chat.user.id == loggedInUser.id">
                        <div  v-if="chat.type == 1">
                                <div class="d-flex align-items-center justify-content-end">
                                    <span class="mx-2">{{ getHourAndMinutes(chat.created_at) }}</span>
                                    <img style="width:200px;" class="rounded my-2" :src="getChatImageUrl(chat.message)"  alt="">
                                </div>
                        </div>
                        <div v-else>
                            <p>
                                <strong class="primary-font">
                                You ( {{ chat.user.username }} ):
                                </strong>
                                <span>
                                    {{ chat.message }}
                                </span>
                                <br>
                                <span>{{ getHourAndMinutes(chat.created_at) }}</span>
                            </p> 
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div v-else>
        <div style="overflow:hidden; height: calc(100vh - 40vh);" class="align-items-center d-flex justify-content-center my-5">
            <h5>Select a user to chat</h5>
        </div>
    </div>

</template>

<script>
export default {
    props: ['currentFilteredUser', 'groupedChats', 'loggedInUser'],
    data() {
    },
    methods: {
        getDay(time) {
            const date = new Date(time);
            const currentDate = new Date();

            let yesterday = new Date(currentDate);
            yesterday.setDate(yesterday.getDate() - 1);

            const formattedDate = date.toISOString().slice(0, 10);
            const formattedCurrentDate = currentDate.toISOString().slice(0, 10);
            const formattedYesterdayDate = yesterday.toISOString().slice(0, 10);  

            if(formattedDate == formattedCurrentDate) {
                return "Today";
            }else if(formattedDate == formattedYesterdayDate) {
                return "yesterday";
            } else {
                return time;
            }
        },

        getHourAndMinutes(dateString) {
            const date = new Date(dateString);
            const currentDate = new Date();
            
            const hour = date.getHours();
            const minutes = date.getMinutes();
            const amPM = hour >= 12 ? 'PM' : 'AM';
            const formattedHour = hour % 12 || 12;
            return `${formattedHour}:${minutes < 10 ? '0' : ''}${minutes} ${amPM}`; // Padding with zero if minutes < 10
        },

        getChatImageUrl(url) {
            return "http://localhost:8000/storage/" + url;        
        }
    }
}
</script>


