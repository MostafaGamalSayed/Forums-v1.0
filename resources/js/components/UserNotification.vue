<template>
<div>
    <li class="nav-item dropdown">
        <a class="nav-link text-white" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fa fa-bell"></i><span class="badge badge-warning" v-text="notifications.length" v-show="notifications.length"></span>
        </a>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
            <div v-if="notifications.length">
                <div v-for="(notification, index) in notifications">
                    <a class="dropdown-item nav-link" href="#" @click.prevent="markAsRead(notification)" v-text="notification.data.message"></a>
                </div>
            </div>
            <div v-else>
                <span class="dropdown-item nav-link">You have no notifications!</span>
            </div>
        </div>
    </li>
</div>
</template>

<script>
export default {
    data() {
        return {
            notifications: [],
            endpoint: '/' + window.App.user.name + '/notifications',
        }
    },
    created() {
        axios.get(this.endpoint).then(this.refresh);
        window.Echo.channel('favorite-item').listen('ItemWasFavorite', e => {
            //console.log(JSON.parse(e.notification.data))
            this.notifications.push(e.notification);
            flash(JSON.parse(e.notification.data).message)

        });
    },
    methods: {
        refresh(response) {
            this.notifications = response.data;
        },
        markAsRead(notification) {
            axios.delete(this.endpoint + '/' + notification.id).then(this.redirect(notification));
        },
        redirect(notification) {
            window.location.href = '/threads/' + notification.data.thread.channel.slug + '/' + notification.data.thread.id;
        },
    },
}
</script>
