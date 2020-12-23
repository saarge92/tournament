<template>
    <div>

        <quarter-final :playoffResults="playoffResults"></quarter-final>
        <semifinal :playoffResults="playoffResults"></semifinal>
        <!--Матчи 3 место -->
        <div style="margin-top:1rem;">
            <h4 class="quarter_final_header">Матч за 3 место</h4>
            <div style="margin-left: 2rem;">
                {{ playoffResults.third_place_tournament[0].winner.name }} -
                {{ playoffResults.third_place_tournament[0].looser.name }}
                <span style="text-decoration: underline">{{ playoffResults.third_place_tournament[0].score }}</span>
            </div>

            <div style="margin-left:2rem">
                <span style="text-decoration: underline">Победитель : {{
                        playoffResults.third_place_tournament[0].winner.name
                    }} </span>
            </div>
        </div>

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

    </div>
</template>

<script>
import QuarterFinalResult from "./QuarterFinalResult";
import SemifinalResult from "./SemifinalResult";

export default {
    name: "PlayOffResult",
    props: ["playoffResults"],
    components: {
        'quarter-final': QuarterFinalResult,
        'semifinal': SemifinalResult
    }
}
</script>

<style scoped>

.quarter_final_header {
    margin-left: 1rem;
    text-decoration: underline;
}

.quarter_final_full_info {
    margin-left: 1rem;
}
</style>
