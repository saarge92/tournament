<template>
    <!--Четверть финалы -->
    <div class="quarter_final_block">
        <h4 id="quarter_final_header">Четверть финал</h4>
        <div class="quarter_final_full_info"
             v-for="quarterFinal in this.playoffResults.quarter_final['result_matches']">
            {{ quarterFinal.team_home.name }} - {{ quarterFinal.team_guest.name }}
            <span style="text-decoration: underline">{{ quarterFinal.score }}</span>
        </div>

        <div class="quarter_final_full_info">
            <span style="text-decoration: underline">Победители : </span>
            <span v-for="(quarterFinal,index) in this.playoffResults.quarter_final['team_winners']">
                {{ quarterFinal.name }} {{ index != 3 ? ',' : null }}
            </span>
        </div>
    </div>
</template>

<script>
export default {
    name: "QuarterFinalResult",
    props: ["playoffResults"]
}
</script>

<style>
.quarter_final_block {
    margin-left: 1rem;
}

.quarter_final_full_info {
    margin-left: 1rem;
}
</style>
