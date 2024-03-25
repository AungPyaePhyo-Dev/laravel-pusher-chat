<template>
    <h6 class="my-4">Recent</h6>
    <div class="" v-for="recent_chat in sortedData" :key="recent_chat.id">
        <div class="d-flex align-items-center my-3" :class="{ active : currentFilteredUser.id == getParticipantUser(recent_chat.participants).id }"  @click="currentUser(getParticipantUser(recent_chat.participants).id)" style="cursor:pointer;">
                <div>
                <img style="border-radius: 50%; width:50px;" :src="getImage(getParticipantUser(recent_chat.participants).id)" alt="">
            </div>
            <div class="w-100 ms-2">
                <div class="d-flex justify-content-between">
                    <div>
                        <span :class="recent_chat.isNewMessage ? 'fw-bold' : '' ">{{ getParticipantUser(recent_chat.participants).username }}</span>
                        <p :class="recent_chat.isNewMessage ? 'fw-bold' : '' ">{{ 
                                recent_chat.last_message.type == 1 ? 'Photo Message' : getLastMessage(recent_chat) 
                            }}
                        </p>
                    </div>
                    <div>
                        <span :class="recent_chat.isNewMessage ? 'fw-bold' : '' ">{{  getLastMessageTime(recent_chat.last_message.created_at) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

    export default {
        props: ['sortedData', 'currentFilteredUser', 'loggedInUserId'],
        data() {
            return {
                
            }
        },
        methods: {
            currentUser(id) {
               this.$emit("currentUser", id)
            },

            getParticipantUser(participants) {
                let logged_user_id = this.loggedInUserId; 
                let participant = participants.find(function(participant) {
                    return participant.user.id != logged_user_id;
                });
                return participant.user;
            },

            getImage(id) {
                return "https://randomuser.me/api/portraits/med/men/" + id + '.jpg';
            },

            getLastMessageTime(dateString)
            {
                const date = new Date(dateString);
                const currentDate = new Date();

                let yesterday = new Date(currentDate);
                yesterday.setDate(yesterday.getDate() - 1);

                const formattedDate = date.toISOString().slice(0, 10);
                const formattedCurrentDate = currentDate.toISOString().slice(0, 10);
                const formattedYesterdayDate = yesterday.toISOString().slice(0, 10);  

                if (formattedDate === formattedCurrentDate) {
                    const hour = date.getHours();
                    const minutes = date.getMinutes();
                    const amPM = hour >= 12 ? 'PM' : 'AM';
                    const formattedHour = hour % 12 || 12;
                    return `${formattedHour}:${minutes < 10 ? '0' : ''}${minutes} ${amPM}`; // Padding with zero if minutes < 10
                }else if(formattedDate === formattedYesterdayDate) {
                    return this.yesterday;
                }else {
                    return formattedDate.slice(5, 10);
                }
            },

        }
    }
</script>

<style>
    .active {
        background-color: #cdd5dd;
        padding:0px 10px;
        border-radius: 10px;
        transition: 0.5s;
    }
</style>