<template>
    <div>
        <!--Четверть финалы -->
        <div class="quarter_final_block">
            <h4 id="quarter_final_header">Четверть финал</h4>
            <div class="quarter_final_full_info"
                 v-for="quarterFinal in playoffResults.quarter_final[0]['result_matches']">
                {{ quarterFinal.team_home.name }} - {{ quarterFinal.team_guest.name }}
                <span style="text-decoration: underline">{{ quarterFinal.score }}</span>
            </div>

            <div style="margin-left:1rem">
                <span style="text-decoration: underline">Победители : </span>
                <span v-for="(quarterFinal,index) in playoffResults.quarter_final[0]['team_winners']">
                {{ quarterFinal.name }} {{ index != 3 ? ',' : null }}
            </span>
            </div>
        </div>

        <!--Полуфиналы -->
        <div style="margin-top:1rem;">
            <h4 class="quarter_final_header">Полуфинал</h4>
            <div style="margin-left: 2rem;" v-for="semifinal in playoffResults.semifinal[0]['result_matches']">
                {{ semifinal.team_home.name }} - {{ semifinal.team_guest.name }}
                <span style="text-decoration: underline">{{ semifinal.score }}</span>
            </div>

            <div style="margin-left:2rem">
                <span style="text-decoration: underline">Победители : </span>
                <span v-for="(semifinal,index) in playoffResults.semifinal[0]['team_winners']">
                {{ semifinal.name }} {{ index != 1 ? ',' : null }}
            </span>
            </div>
        </div>

    </div>
</template>

<script>
export default {
    name: "PlayOffResult",
    props: ["playoffResults"]
}
</script>

<style scoped>
.quarter_final_block {
    margin-top: 1rem;
    margin-left: 1rem;
}

.quarter_final_header {
    margin-left: 1rem;
    text-decoration: underline;
}

.quarter_final_full_info {
    margin-left: 1rem;
}
</style>
