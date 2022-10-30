<template>
    <div class="post-view">
        <template v-if="currentUser && (post.id == null || currentUser.id == post.user_id)">
            <form @submit.prevent="save()">
                <div class="mb-2">
                    <InputLabel :value="$t('post.status_id')" />
                    <InputLabel v-for="status in statuses" v-bind:key="status.id" :for="`status_${status.id}`" class="inline-block mr-4" >
                        <input type="radio" v-model="form.status_id" :value="status.id" :id="`status_${status.id}`" />
                        {{ $t(`post.status.${status.code}`) }}
                    </InputLabel>
                </div>
                <div class="mb-2">
                    <InputLabel for="title" :value="$t('post.title')" />
                    <TextInput id="title" class="mt-1 block w-full" v-model="form.title" required autofocus autocomplete="off"
                               type="text"
                    />
                    <InputError class="mt-2" :message="form.errors.title" />
                </div>
                <div class="mb-2">
                    <InputLabel for="content" :value="$t('post.content')" />
                    <TextareaInput id="content" class="mt-1 block w-full" v-model="form.content" required autocomplete="off" />
                    <InputError class="mt-2" :message="form.errors.content" />
                </div>
                <div class="flex items-center justify-end mt-4">
                    <DangerButton class="ml-4" :class="{ 'opacity-25': form.processing}" :disabled="form.processing"
                                  @click="deleteItem()"
                    >
                        {{ $t('general.action.delete') }}
                    </DangerButton>
                    <PrimaryButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing"
                                   @click="save()"
                    >
                        {{ $t('general.action.save') }}
                    </PrimaryButton>
                </div>
            </form>
        </template>
        <div v-else class="bg-white mb-3 p-3">
            <h1 class="font-bold mb-3">{{ post.title }}</h1>
            <div class="bg-white whitespace-pre">
                {{ post.content }}
            </div>
            <div class="flex mt-3">
                <div class="flex-1 font-italic">
                    {{ post.user.name }}
                </div>
                <div>
                    {{ $filters.formatDateTime(post.published_at) }}
                </div>
            </div>
        </div>
        <div v-if="post.id">
            <PostReactions :post="post" :reactions="reactions" />
        </div>
    </div>
</template>

<script src="./View.js" />
