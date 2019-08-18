var educationsContainer = $('#resume_educations');
var workExperiencesContainer = $('#resume_workExperiences');
var proSkillsContainer = $('#resume_proSkills');

var indexEducation = educationsContainer.find('div.row').length;
var indexWorkExp = workExperiencesContainer.find('div.row').length;
var indexProSkill = proSkillsContainer.find('div.row').length;

var addEducationBtn = $(`<div class="col-12 btn-new-education">
                            <a class="add-new-field float-right" href="#">+ Ajouter une éducation</a>
                        </div>`);
var addWorkExpBtn = $(`<div class="col-12 btn-new-work-exp">
                            <a class="add-new-field float-right" href="#">+ Ajouter une expérience</a>
                        </div>`);
var addProSkillBtn = $(`<div class="col-12 btn-new-pro-skill">
                            <a class="add-new-field float-right" href="#">+ Ajouter une compétence</a>
                        </div>`);

educationsContainer.append(addEducationBtn);
workExperiencesContainer.append(addWorkExpBtn);
proSkillsContainer.append(addProSkillBtn);

addEducationBtn.on('click', function (e) {
    addEducation(educationsContainer);
    e.preventDefault();
    return false;
});
addWorkExpBtn.on('click', function (e) {
    addWorkExperience(workExperiencesContainer);
    e.preventDefault();
    return false;
});
addProSkillBtn.on('click', function (e) {
    addProSkill(proSkillsContainer);
    e.preventDefault();
    return false;
});

if (indexEducation == 0)
    addEducation(educationsContainer);
else {
    educationsContainer.children('div.row').each(function () {
        addDeleteEducationLink($(this))
    });
}

if (indexWorkExp == 0)
    addWorkExperience(workExperiencesContainer);
else {
    workExperiencesContainer.children('div.row').each(function () {
        addDeleteWorkExperienceLink($(this))
    });
}

if (indexProSkill == 0)
    addProSkill(proSkillsContainer);
else {
    proSkillsContainer.children('div.row').each(function () {
        addDeleteProSkillLink($(this))
    });
}

function addWorkExperience(container) {
    if(typeof container.attr('data-prototype') !== 'undefined') {
        var prototype = $(container.attr('data-prototype').replace(/___name___/g, (indexWorkExp + 1)));
        addDeleteWorkExperienceLink(prototype);

        prototype.addClass('transition');
        prototype.insertBefore('.btn-new-work-exp');
        indexWorkExp++;
    }
}

function addDeleteWorkExperienceLink(prototype) {
    var deleteLink = $(`<button class="btn delete-item"><i class="fas fa-trash"></i></button>`);
    var wokExpRow = prototype.find('.work-experience-row');
    wokExpRow.append(deleteLink);

    deleteLink.on('click', function (e) {
        prototype.hide('normal', function () {
            prototype.remove();
        })
        e.preventDefault();
        return false;
    }).hover(function () {
        wokExpRow.addClass('bordered shadow-lg')
    }, function () {
        wokExpRow.removeClass('bordered shadow-lg')
    })
}

function addEducation(container) {
    if(typeof container.attr('data-prototype') !== 'undefined') {
        var prototype = $(container.attr('data-prototype').replace(/___name___/g, (indexEducation + 1)));
        addDeleteEducationLink(prototype);

        prototype.addClass('transition');
        prototype.insertBefore('.btn-new-education');
        indexEducation++;
    }
}

function addDeleteEducationLink(prototype) {
    var deleteLink = $(`<button class="btn delete-item"><i class="fas fa-trash"></i></button>`);
    var educRow = prototype.find('.education-row');
    educRow.append(deleteLink);

    deleteLink.on('click', function (e) {
        prototype.hide('normal', function () {
            prototype.remove();
        })
        e.preventDefault();
        return false;
    }).hover(function () {
        educRow.addClass('bordered shadow-lg')
    }, function () {
        educRow.removeClass('bordered shadow-lg')
    })
}

function addProSkill(container) {
    if(typeof container.attr('data-prototype') !== 'undefined') {
        var prototype = $(container.attr('data-prototype').replace(/___name___/g, (indexProSkill + 1)));
        addDeleteProSkillLink(prototype);

        prototype.addClass('transition');
        prototype.insertBefore('.btn-new-pro-skill');
        indexProSkill++;
    }
}

function addDeleteProSkillLink(prototype) {
    var deleteLink = $(`<button class="btn delete-item"><i class="fas fa-trash"></i></button>`);
    var proSkillsRow = prototype.find('.pro-skill-row');
    proSkillsRow.append(deleteLink);

    deleteLink.on('click', function (e) {
        prototype.hide('normal', function () {
            prototype.remove();
        })
        e.preventDefault();
        return false;
    }).hover(function () {
        proSkillsRow.addClass('bordered shadow-lg')
    }, function () {
        proSkillsRow.removeClass('bordered shadow-lg')
    })
}