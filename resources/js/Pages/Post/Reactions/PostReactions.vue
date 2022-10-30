<template>
    <div class="post-reaction">
        <div v-for="reaction in reactions"
             class="inline-flex items-center px-4 py-2 border rounded-md"
             :class="{ 'bg-emerald-700 text-white': hasReaction(reaction.id), 'cursor-pointer': currentUser }"
             :active="true"
             @click="toggleReaction(reaction)"
        >
            {{ $t(`reaction.${reaction.code}`)}}
            {{ post['total' + reaction.code] }}
        </div>
    </div>
</template>

<script src="./PostReactions.js" />
