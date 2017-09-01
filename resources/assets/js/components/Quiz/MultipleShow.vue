<template lang="html">

	<div class="multiple">
		<!-- FORM TO ANSWER Multiple choice QUESTION -->
	    <form method="Post">
	    	
	    	<label>Choose your answer:</label>
	    	<div align="left" v-for="multiple in liveQuestion.multiple"><br>
	    		<li>
    				{{multiple.answer}}
				</li>
		    </div>

      
	    </form>

	</div>
       
    

</template>

<script>
    export default {
        props: ['liveQuestion'],
        
    }
</script>

<style lang="css">

</style>