<template>
    <div>
        <img id="loadingImage" v-show="isLoading" src="/storage/preloader.gif" alt="">
        <center>Турнир {{ tournamentName }}</center>
        <first-division-info :division-name="firstDivisionName"
                             :division-results="firstDivisionResults"
                             :division-teams="firstDivisionTeams"
        ></first-division-info>
        <second-division-info :division-name="secondDivisionName"
                              :division-results="secondDivisionResults"
                              :division-teams="secondDivisionTeams"
        ></second-division-info>

        <div id="buttons-block">
            <button :disabled="isLoading" class="btn btn-primary" v-on:click="generateTournament">
                <i class="fas fa-plus"></i>
                Генерация турнира
            </button>

            <div style="margin-top:1rem">
                <button :disabled="isLoading" v-if="this.tournamentId!=null" class="btn btn-danger"
                        v-on:click="generatePlayOffResults">
                    <i class="fas fa-check"></i>
                    Генерация Плей-офф
                </button>
            </div>

            <div v-if="this.playOffResults!=null">
                <center style="color:red"><h2>Результаты плей-офф</h2></center>
                <playoff-info :playoffResults="playOffResults"></playoff-info>
            </div>
        </div>
    </div>
</template>

<script>
import {generateTournamentsData, getTeamsByDivision} from '../services/qualification_service'
import {generatePlayOff} from "../services/playoff_service"

import DivisionResult from "./DivisionResult";
import PlayOffResult from "./PlayOffResult";

export default {
    data() {
        return {
            tournamentId: null,
            tournamentName: null,
            firstDivisionName: null,
            secondDivisionName: null,
            tables: [],
            firstDivisionResults: [],
            firstDivisionTeams: [],
            secondDivisionTeams: [],
            secondDivisionResults: [],
            playOffResults: null,
            isLoading: false
        }
    },
    async mounted() {

    },
    methods: {
        async generateTournament() {
            this.isLoading = true
            const generatedTournamentData = await generateTournamentsData()
            console.log(generatedTournamentData)
            await this.fillDataAboutQualification(generatedTournamentData).catch(error => {
                this.loading = false;
                alert(error)
            })
            this.isLoading = false
        },
        async generatePlayOffResults() {
            this.isLoading = true;
            this.playOffResults = await generatePlayOff(this.tournamentId).catch(error => {
                this.isLoading = false
                alert(error)
            })
            console.log(this.playOffResults)
            this.isLoading = false;
        },
        async fillDataAboutQualification(tournamentResults) {
            this.tables = tournamentResults.tables
            this.tournamentName = tournamentResults.tournament_name;
            this.tournamentId = tournamentResults.tournament_id
            this.firstDivisionResults = tournamentResults.tables[0].results
            this.firstDivisionName = tournamentResults.tables[0].division_name
            this.firstDivisionTeams = await getTeamsByDivision(tournamentResults.tables[0].division_id)

            this.secondDivisionName = tournamentResults.tables[1].division_name
            this.secondDivisionTeams = await getTeamsByDivision(tournamentResults.tables[1].division_id)
            this.secondDivisionResults = tournamentResults.tables[1].results;

        }
    },
    components: {
        'first-division-info': DivisionResult,
        'second-division-info': DivisionResult,
        'playoff-info': PlayOffResult
    }
}
</script>

<style scoped>
#buttons-block {
    margin-left: 1rem;
}

#loadingImage {
    position: absolute;
    margin: 0 auto;
    width: 100%;
    top: 0;
}
</style>
