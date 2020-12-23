<template>
    <div id="division_first">
        <p class="division_name">Дивизион {{ firstDivisionName }}</p>
        <table id="firstDivisionTable" class="table">
            <thead>
            <tr>
                <th>&nbsp;</th>
                <th v-for="firstTeamDivision in firstDivisionTeams">
                    {{ firstTeamDivision.name }}
                </th>
                <th>Score</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(firstTeamDivisionColumn,index) in firstDivisionTeams">
                <td>
                    {{ firstTeamDivisionColumn.name }}
                </td>
                <td v-for="(firstTeamDivisionRow,indexColumn) in firstDivisionTeams">
                    {{
                        indexColumn == index ? null : firstDivisionResults[index][firstTeamDivisionColumn.name]
                            [firstTeamDivisionRow.name]
                    }}
                </td>
                <td> {{ Object.values(firstDivisionResults[index])[1] }}</td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
export default {
    props: ["firstDivisionName", "firstDivisionTeams", "firstDivisionResults"],
    data() {
        return {}
    },
    methods: {
        mapFirstDivisionResults() {
        }
    },
    mounted() {

    },
    watch: {}

}
</script>

<style scoped>
.division_name {
    text-decoration: underline;
    color: red;
    text-align: center;
}
</style>
