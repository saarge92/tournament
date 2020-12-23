<template>
    <!--Матчи 3 место -->
    <div style="margin-top:1rem;">
        <h4 class="quarter_final_header">Матч за 3 место</h4>
        <div style="margin-left: 2rem;">
            {{ playoffResults.third_place_tournament.winner.name }} -
            {{ playoffResults.third_place_tournament.looser.name }}
            <span style="text-decoration: underline">{{ playoffResults.third_place_tournament.score }}</span>
        </div>

        <div style="margin-left:2rem">
                <span style="text-decoration: underline">Победитель : {{
                        playoffResults.third_place_tournament.winner.name
                    }} </span>
        </div>
    </div>
</template>

<script>
export default {
    name: "ThirdPlaceResult",
    props: ['playoffResults']
}
</script>

<style scoped>

</style>
