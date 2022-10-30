<template>
    <div class="post-list">
        <div v-if="currentUser" class="text-right mb-4">
            <PrimaryButton>
                <Link :href="route('posts.viewnew')">
                    {{ $t('post.action.new') }}
                </Link>
            </PrimaryButton>
        </div>
        <div>
            <div v-for="post in posts.data" :key="post.id" class="p-2 bg-white mb-3">
                <Link class="flex pb-1 mb-3" :href="this.route('posts.view', {id :post.id})">
                    <div class="flex-1 font-bold text-lg">
                        {{ post.title }}
                    </div>
                    <div>
                        <span class="p-1 pb-0 pt-0 rounded-md text-white bg-gray-700" :class="{ 'bg-emerald-600': post.status.code == 'PUBLISHED' }" >
                            {{ $t(`post.status.${post.status.code}`) }}
                        </span>
                    </div>
                </Link>
                <div class="flex">
                    <div class="flex-1">
                        <PostReactions :post="post" :reactions="reactions" />
                    </div>
                    <div>
                        <div>
                            {{ post.user.name }}
                        </div>
                        <div>
                            {{ $filters.formatDateTime(post.published_at) }}
                        </div>
                    </div>
                </div>
            </div>
            <Pagination :links="posts.links" />
        </div>
    </div>
</template>

<script src="./List.js" />
