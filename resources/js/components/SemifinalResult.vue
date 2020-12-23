<template>
    <!--Полуфиналы -->
    <div style="margin-top:1rem;">
        <h4 class="quarter_final_header">Полуфинал</h4>
        <div style="margin-left: 2rem;" v-for="semifinal in playoffResults.semifinal['result_matches']">
            {{ semifinal.team_home.name }} - {{ semifinal.team_guest.name }}
            <span style="text-decoration: underline">{{ semifinal.score }}</span>
        </div>

        <div style="margin-left:2rem">
            <span style="text-decoration: underline">Победители : </span>
            <span v-for="(semifinal,index) in playoffResults.semifinal['team_winners']">
                {{ semifinal.name }} {{ index != 1 ? ',' : null }}
            </span>
        </div>
    </div>
</template>

<script>
export default {
    name: "SemifinalResult",
    props: ['playoffResults']
}
</script>

<style>

</style>
