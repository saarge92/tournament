<template>
    <div>

        <quarter-final :playoffResults="playoffResults"></quarter-final>
        <semifinal :playoffResults="playoffResults"></semifinal>
        <third-pace-final :playoffResults="playoffResults"></third-pace-final>
        <final :playoffResults="playoffResults"></final>
    </div>
</template>

<script>
import QuarterFinalResult from "./QuarterFinalResult";
import SemifinalResult from "./SemifinalResult";
import ThirdPlaceResult from "./ThirdPlaceResult";
import FinalResult from "./FinalResult";

export default {
    name: "PlayOffResult",
    props: ["playoffResults"],
    components: {
        'quarter-final': QuarterFinalResult,
        'semifinal': SemifinalResult,
        'third-pace-final': ThirdPlaceResult,
        'final': FinalResult
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
