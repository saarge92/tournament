<template>
    <!-- Финал -->
    <div style="margin-top:1rem;">
        <h4 class="quarter_final_header">Финал!</h4>
        <div style="margin-left: 2rem;">
            {{ this.playoffResults.final_tournament.winner.name }} -
            {{ this.playoffResults.final_tournament.looser.name }}
            <span style="text-decoration: underline">{{ this.playoffResults.final_tournament.score }}</span>
        </div>

        <div style="margin-left:2rem">
                <span style="text-decoration: underline">Победитель финала : {{
                        this.playoffResults.final_tournament.winner.name
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
