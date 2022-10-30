<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/inertia-vue3';

const props = defineProps(['links', 'active']);
</script>

<template>
    <div v-if="links.length > 3">
        <div class="flex flex-wrap -mb-1">
            <template v-for="(link, p) in links" :key="p">
                <div v-if="link.url === null" class="mr-1 mb-1 px-4 py-3 text-sm leading-4 text-gray-400 border rounded">
                    <template v-if="link.label.startsWith('pagination')">
                        {{ $t(link.label) }}
                    </template>
                    <template v-else>
                        {{ link.label }}
                    </template>
                </div>
                <Link v-else
                              class="mr-1 mb-1 px-4 py-3 text-sm leading-4 border rounded hover:bg-white focus:border-indigo-500 focus:text-indigo-500"
                              :class="{ 'bg-blue-700 text-white': link.active }" :href="link.url">
                    <template v-if="link.label.startsWith('pagination')">
                        {{ $t(link.label) }}
                    </template>
                    <template v-else>
                        {{ link.label }}
                    </template>
                </Link>
            </template>
        </div>
    </div>
</template>
