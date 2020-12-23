<template>
    <!-- Финал -->
    <div style="margin-top:1rem;">
        <h4 class="quarter_final_header">Финал!</h4>
        <div style="margin-left: 2rem;">
            {{ playoffResults.final_tournament[0].winner.name }} -
            {{ playoffResults.final_tournament[0].looser.name }}
            <span style="text-decoration: underline">{{ playoffResults.final_tournament[0].score }}</span>
        </div>

        <div style="margin-left:2rem">
                <span style="text-decoration: underline">Победитель финала : {{
                        playoffResults.final_tournament[0].winner.name
                    }} </span>
        </div>
    </div>
</template>

<script>
export default {
    name: "FinalResult",
    props: ['playoffResults']
}
</script>

<style scoped>

</style>
